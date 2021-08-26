<?php

namespace App\Http\Controllers;

use App\Rules\Type;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all courses of the current teacher
        $courses = Course::where("teacher_id", "=", auth()->user()->id)->orderBy("created_at", "DESC")->get();
        return view("courses.index")->with("courses", $courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->role !== "Teacher") {
            return view("/")->with("error", "Only teachers can create courses");
        }

        return view("courses.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate Request
        $request->validate([
           "code" => "required|max:6",
           "name" => "required",
           "ects" => "required",
           "type" => "required",
            "description" => "required",
        ]);

        if(auth()->user()->role !== "Teacher") {
            return view("/")->with("error", "Only teachers can create courses");
        }

        // Create New Course
        $course = new Course();
        $course->teacher_id = auth()->user()->id;
        $course->code = $request->input("code");
        $course->name = $request->input("name");
        $course->ects = $request->input("ects");
        $course->type = $request->input("type");
        $course->description = $request->input("description");
        $course->save();

        return redirect("/teachers/courses")->with("success", "Course Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Check if course exists
        $course = Course::findOrFail($id);

        if(auth()->user()->role === "ADMIN") {
            return redirect("/admins")->with("error", "Only teachers and students can view courses");
        }

        return view("courses.show")->with("course", $course);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check if course exists
        $course = Course::findOrFail($id);

        if(auth()->user()->role !== "Teacher") {
            return redirect("/")->with("error", "Only teachers can edit courses");
        } elseif(auth()->user()->id !== $course->teacher_id) {
            return redirect("/teachers")->with("error", "You cannot edit other teachers's courses");
        }

        return view("courses.edit")->with("course", $course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate Request
        $request->validate([
            "code" => "required|max:6",
            "name" => "required",
            "ects" => "required",
            "type" => "required",
            "description" => "required",
        ]);

        // Check if course exists
        $course = Course::findOrFail($id);

        if(auth()->user()->role !== "Teacher") {
            return view("/")->with("error", "Only teachers can edit courses");
        } elseif(auth()->user()->id !== $course->teacher_id) {
            return redirect("/teachers")->with("error", "You cannot edit other teachers's courses");
        }

        // Update course
        $course->code = $request->input("code");
        $course->name = $request->input("name");
        $course->ects = $request->input("ects");
        $course->type = $request->input("type");
        $course->description = $request->input("description");
        $course->save();

        return redirect("/teachers/courses")->with("success", "Course Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check if course exists
        $course = Course::findOrFail($id);

        if(auth()->user()->role !== "Teacher") {
            return view("/")->with("error", "Only teachers can delete courses");
        } elseif(auth()->user()->id !== $course->teacher_id) {
            return redirect("/teachers")->with("error", "You cannot delete other teachers's courses");
        }

        // Delete course
        $course->delete();

        return redirect("/teachers/courses")->with("success", "Course Deleted");
    }
}
