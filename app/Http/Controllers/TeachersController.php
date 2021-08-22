<?php

namespace App\Http\Controllers;

use App\Rules\Type;
use Illuminate\Http\Request;
use App\Models\Course;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("teachers.index")->with("courses", Course::all());
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
        $request->validate([
            "code" => "required|max:6",
            "name" => "required",
            "ects" => "required",
            "type" => ["required", new Type]
        ]);

        $course = new Course;
        $course->teacher_id = auth()->user()->id;
        $course->code = $request->input("code");
        $course->name = $request->input("name");
        $course->ects = $request->input("ects");
        $course->type = $request->input("type");
        $course->save();

        return redirect("/teachers")->with("success", "Course Added");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
