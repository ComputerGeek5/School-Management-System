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
        $courses = Course::all()->where("teacher_id", "=", auth()->user()->id);
//        dd($courses);
        return view("courses.index")->with("courses", $courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $request->validate([
           "code" => "required|max:6",
           "name" => "required",
           "ects" => "required",
           "type" => ["required", new Type()]
        ]);

        if(auth()->user()->role !== "Teacher") {
            return view("/")->with("error", "Only teachers can create courses");
        }

        $course = new Course();
        $course->teacher_id = auth()->user()->id;
        $course->code = $request->input("code");
        $course->name = $request->input("name");
        $course->ects = $request->input("ects");
        $course->type = $request->input("type");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
