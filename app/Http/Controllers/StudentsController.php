<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $request->validate([
            "name" => "required",
            "email" => "required|unique:users,email",
            "password" => "required|min:8"
        ]);

        $user = new User();
        $user->name = $request->input("name");
        $user->role = "Student";
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->save();

        $student = new Student();
        $student->id = $user->id;
        $student->name = $request->input("name");
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
        $student = Student::find($id);

        if(auth()->user()->role === "Teacher") {
            return redirect("/")->with("error", "You cannot view other students's profiles");
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
        $student = Student::find($id);

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
        $request->validate([
            "name" => "required",
        ]);

        $user = User::find($id);

        if($user->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        $user->name = $request->input("name");
        $user->save();

        $student = Student::find($id);
        $student->name = $request->input("name");
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
        $user = User::find($id);

        if(auth()->user()->role !== "ADMIN") {
            return redirect("/")->with("error", "You cannot delete other users's accounts");
        }

        $user->delete();

        $student = Student::find($id);
        $student->delete();

        if(auth()->user()->id !== $student->id) {
            return redirect("/admins")->with("success", "Student Deleted");
        }

        return redirect("/login")->with("success", "Account Deleted");
    }

    public function selected() {
        $student = Student::find(auth()->user()->id);
        $courses_ids = array_reverse($student->courses);
        $courses = array();

        foreach($courses_ids as $course_id) {
            $courses[] = Course::find($course_id);
        }

        return view("students.selected")->with("courses", $courses);
    }

    public function take() {
        $courses = Course::all();
        return view("students.take")->with("courses", $courses);
    }

    public function enroll($id) {
        $student = Student::find(auth()->user()->id);
        $courses = $student->courses;
        $courses[] = $id;
        $student->courses = $courses;
        $student->save();

        return redirect("/students/selected")->with("success", "Course Selected");
    }

    public function unenroll($id) {
        $student = Student::find(auth()->user()->id);
        $courses = $student->courses;
        $id = array_search($id, $courses);
        unset($courses[$id]);
        $student->courses = $courses;
        $student->save();

        return redirect("/students/selected")->with("success", "Course Unselected");
    }
}
