<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::where("id", "!=", auth()->user()->id)->orderBy("name", "ASC")->get();
        return view("teachers.index")->with("teachers", $teachers);
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
    public function store(Request $request)
    {
        // Default User Password
        $default_user_password = "12345678";

        $request->validate([
            "name" => "required",
            "email" => "required|unique:users,email",
            "title" => "required",
            "faculty" => "required",
            "image" => "image|nullable|max:1999",
        ]);

        $user = new User();
        $user->name = $request->input("name");
        $user->role = "Teacher";
        $user->email = $request->input("email");
        $user->password = Hash::make($default_user_password);
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

        $teacher = new Teacher();
        $teacher->id = $user->id;
        $teacher->name = $request->input("name");
        $teacher->email = $request->input("email");
        $teacher->title = $request->input("title");
        $teacher->faculty = $request->input("faculty");
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
        $teacher = Teacher::find($id);

        if(auth()->user()->role === "Student") {
            return redirect("/")->with("error", "You cannot view other teachers's profiles");
        }

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
        $teacher = Teacher::find($id);

        if($teacher->id !== auth()->user()->id) {
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
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required",
            "title" => "required",
            "faculty" => "required",
            "about" => "required",
            "image" => "image|nullable|max:1999",
        ]);

        $user = User::find($id);

        if($user->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        $user->name = $request->input("name");
        if(!empty($request->input("password"))) {
            $user->password = Hash::make($request->input("password"));
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

        $teacher = Teacher::find($id);
        $teacher->name = $request->input("name");
        $teacher->title = $request->input("title");
        $teacher->faculty = $request->input("faculty");
        $teacher->about = $request->input("about");

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
        $user = User::find($id);

        if(auth()->user()->role === "Student") {
            return redirect("/")->with("error", "You cannot delete other users's accounts");
        }

        $teacher = Teacher::find($id);

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
