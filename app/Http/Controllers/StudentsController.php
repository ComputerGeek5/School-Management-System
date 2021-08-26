<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentsController extends Controller
{
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
    public function store(Request $request)
    {
        // Default User Password
        $default_user_password = "12345678";

        //  Validate Request
        $request->validate([
            "name" => "required",
            "email" => "required|unique:users,email",
            "graduation_year" => "required",
            "program" => "required",
            "image" => "image|nullable|max:1999",
        ]);

        // Create new user
        $user = new User();
        $user->name = $request->input("name");
        $user->role = "Student";
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

        // Create new student
        $student = new Student();
        $student->id = $user->id;
        $student->email = $request->input("email");
        $student->name = $request->input("name");
        $student->graduation_year = $request->input("graduation_year");
        $student->program = $request->input("program");
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
    public function update(Request $request, $id)
    {
        // Validate Request
        $request->validate([
            "name" => "required",
            "program" => "required",
            "graduation_year" => "required",
            "about" => "required",
            "image" => "image|nullable|max:1999",
        ]);

        // Check if user exists
        $user = User::findOrFail($id);

        if($user->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        // Update User
        $user->name = $request->input("name");

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
        $student->name = $request->input("name");
        $student->about = $request->input("about");
        $student->graduation_year = $request->input("graduation_year");
        $student->program = $request->input("program");

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

    public function take() {
        // Get all courses
        $courses = Course::all();

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
