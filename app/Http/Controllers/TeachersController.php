<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherStoreRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Models\Student;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeachersController extends Controller
{
    public function index(Request $request){
        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the courses table
        $teachers = Teacher::query()
            ->where("id", "!=", auth()->user()->id)
            ->where('name', 'LIKE', "%{$search}%")
            ->simplePaginate(4);

        // Return the search view with the results
        return view('teachers.index')->with("teachers", $teachers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("teachers.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherStoreRequest $request)
    {
        // Validate Request
        $validated = $request->validated();

        // Create New User
        $user = new User();
        $user->name = $validated["name"];
        $user->role = "Teacher";
        $user->email = $validated["email"];
        $user->password = Hash::make($validated["password"]);
        $user->save();

        // Handle image upload
        if($request->hasFile("image")) {
            // Get full name
            $fileNameWithExt = $request->file("image")->getClientOriginalName();
            // Get only name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file("image")->getClientOriginalExtension();
            // File name to store
            $fileNameToStore = $fileName."_".time().".".$extension;
            // Upload image
            $request->file("image")->storeAs("public/images", $fileNameToStore);
        } else {
            $fileNameToStore = "noimage.jpg";
        }

        // Create New Teacher
        $teacher = new Teacher();
        $teacher->id = $user->id;
        $teacher->name = $validated["name"];
        $teacher->email = $validated["email"];
        $teacher->about = $validated["about"];
        $teacher->title = $validated["title"];
        $teacher->faculty = $validated["faculty"];
        $teacher->image = $fileNameToStore;
        $teacher->save();

        return redirect("/admins")->with("success", "Teacher Created");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Check if teacher exists
        $teacher = Teacher::findOrFail($id);

        return view("teachers.show")->with("teacher", $teacher);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check if teacher exists
        $teacher = Teacher::findOrFail($id);

        if(auth()->user()->id !== $teacher->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        return view("teachers.edit")->with("teacher", $teacher);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherUpdateRequest $request, $id)
    {
        // Validate Request
        $validated = $request->validated();

        // Find user
        $user = User::findOrFail($id);

        if($user->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        // Update user
        $user->name = $validated["name"];
        if(!empty($validated["password"])) {
            $user->password = Hash::make($validated["password"]);
        }
        $user->save();

        // Handle image upload
        if($request->hasFile("image")) {
            // Get full name
            $fileNameWithExt = $request->file("image")->getClientOriginalName();
            // Get only name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file("image")->getClientOriginalExtension();
            // File name to store
            $fileNameToStore = $fileName."_".time().".".$extension;
            // Upload image
            $request->file("image")->storeAs("public/images", $fileNameToStore);
        }

        // Check if teacher exists
        $teacher = Teacher::findOrFail($id);
        $teacher->name = $validated["name"];
        $teacher->title = $validated["title"];
        $teacher->faculty = $validated["faculty"];
        $teacher->about = $validated["about"];

        // Update image if selected
        if($request->hasFile("image")) {
            Storage::delete("public/images/".$teacher->image);
            $teacher->image = $fileNameToStore;
        }

        $teacher->save();

        return redirect("/teachers/$id")->with("success", "Profile Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if(auth()->user()->role === "Student") {
            return redirect("/")->with("error", "You cannot delete other users's accounts");
        }

        $teacher = Teacher::findOrFail($id);

        // Unenroll all students from this teacher's courses
        $students = Student::all();
        $courses = $teacher->courses;

        foreach($courses as $course) {
            foreach ($students as $student) {
                $selected = $student->courses;

                if (in_array($course->id, $selected)) {
                    $selected_id = array_search($course->id, $selected);
                    unset($selected[$selected_id]);
                    $student->courses = $selected;
                    $student->save();
                }
            }
            $course->delete();
        }

        if($teacher->image !== "noimage.jpg") {
            Storage::delete("public/images/".$teacher->image);
        }

        $teacher->delete();

        if(auth()->user()->id !== $teacher->id) {
            $user->delete();
            return redirect("/admins")->with("success", "Teacher Deleted");
        }

        auth()->logout();
        $user->delete();

        return redirect("/login")->with("success", "Account Deleted");
    }
}
