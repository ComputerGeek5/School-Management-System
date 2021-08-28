<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentsController extends Controller
{
    public function search(Request $request){
        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the courses table
        $students = Student::query()
            ->where("id", "!=", auth()->user()->id)
            ->where('name', 'LIKE', "%{$search}%")
            ->get();

        // Return the search view with the results
        return view('students.search')->with("students", $students);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all users except the authenticated one
        $students = Student::where("id", "!=", auth()->user()->id)->orderBy("name", "ASC")->get();
        return view("students.index")->with("students", $students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("students.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentStoreRequest $request)
    {
        // Default User Password
        $default_user_password = "12345678";

        //  Validate Request
        $validated = $request->validated();

        // Create new user
        $user = new User();
        $user->name = $validated["name"];
        $user->role = "Student";
        $user->email = $validated["email"];
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

        // Create new student
        $student = new Student();
        $student->id = $user->id;
        $student->email = $validated["email"];
        $student->name = $validated["name"];
        $student->about = $validated["about"];
        $student->graduation_year = $validated["graduation_year"];
        $student->program = $validated["program"];
        $student->image = $fileNameToStore;
        $student->save();

        return redirect("/admins")->with("success", "Student Created");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Check if student exists
        $student = Student::findOrFail($id);

        if(auth()->user()->role === "Teacher") {
            return redirect("/")->with("error", "You cannot view students's profiles");
        }

        return view("students.show")->with("student", $student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check if student exists
        $student = Student::findOrFail($id);

        if($student->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        return view("students.edit")->with("student", $student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentUpdateRequest $request, $id)
    {
        // Validate Request
        $validated = $request->validated();

        // Check if user exists
        $user = User::findOrFail($id);

        if($user->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        // Update User
        $user->name = $validated["name"];

        // Update password if not empty
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

        // Check if student exists
        $student = Student::findOrFail($id);

        // Update student
        $student->name = $validated["name"];
        $student->about = $validated["about"];
        $student->graduation_year = $validated["graduation_year"];
        $student->program = $validated["program"];

        // Update image if selected
        if($request->hasFile("image")) {
            Storage::delete("public/images/".$student->image);
            $student->image = $fileNameToStore;
        }

        $student->save();

        return redirect("/students/$id")->with("success", "Profile Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check if user exists
        $user = User::findOrFail($id);

        if(auth()->user()->role === "Teacher") {
            return redirect("/")->with("error", "You cannot delete other users's accounts");
        }

        // Check if student exists
        $student = Student::findOrFail($id);

        // Delete image if not default
        if($student->image !== "noimage.jpg") {
            Storage::delete("public/images/".$student->image);
        }

        // Log out and delete account
        $student->delete();

        if(auth()->user()->id !== $student->id) {
            $user->delete();
            return redirect("/admins")->with("success", "Student Deleted");
        }

        auth()->logout();
        $user->delete();

        return redirect("/login")->with("success", "Account Deleted");
    }

    public function selected() {
        // Check if student exists
        $student = Student::findOrFail(auth()->user()->id);
        $courses_ids = array_reverse($student->courses);
        $courses = array();

        // Display selected courses
        foreach($courses_ids as $course_id) {
            $courses[] = Course::findOrFail($course_id);
        }

        return view("students.selected")->with("courses", $courses);
    }

    public function take(Request $request) {
        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the users table
        $courses = Course::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->simplePaginate(4);

        // Check if student exists
        $student = Student::findOrFail(auth()->user()->id);
        $courses_ids = $student->courses;

        return view("students.take", [
            "courses" => $courses,
            "courses_ids" => $courses_ids
        ]);
    }

    public function enroll($id) {
        // Check if course exists
        Course::findOrFaiL($id);

        // Check if student exists
        $student = Student::findOrFail(auth()->user()->id);
        $courses = $student->courses;

        // Add course id to selected courses
        if(in_array($id, $courses)) {
            return redirect("/students/take")->with("error", "You are already enrolled in that course");
        }

        $courses[] = $id;
        $student->courses = $courses;
        $student->save();

        return redirect("/students/take")->with("success", "Course Selected");
    }

    public function unenroll($id) {
        // Check if course exists
        $course = Course::findOrFaiL($id);

        // Check if student exists
        $student = Student::findOrFail(auth()->user()->id);
        $courses = $student->courses;

        // Remove course id from selected courses
        if(!in_array($id, $courses)) {
            return redirect("/students/selected")->with("error", "You are not enrolled in that course");
        }

        $id = array_search($id, $courses);

        unset($courses[$id]);
        $student->courses = $courses;
        $student->save();

        return redirect("/students/selected")->with("success", "Course Unselected");
    }
}
