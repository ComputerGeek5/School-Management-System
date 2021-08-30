<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Rules\Type;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;

class CoursesController extends Controller
{
    public function index(Request $request){
        $this->authorize("viewAny", Course::class);

        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the courses table
        $courses = Course::query()
            ->where("teacher_id", "=", auth()->user()->id)
            ->where('name', 'LIKE', "%{$search}%")
            ->simplePaginate(4);

        // Return the search view with the results
        return view('courses.index')->with("courses", $courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize("create", Course::class);

        return view("courses.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseStoreRequest $request)
    {
        $this->authorize("create", Course::class);

        // Validate Request
        $validated = $request->validated();

        // Create New Course
        $course = new Course();
        $course->teacher_id = auth()->user()->id;
        $course->code = $validated["code"];
        $course->name = $validated["name"];
        $course->ects = $validated["ects"];
        $course->type = $validated["type"];
        $course->description = $validated["description"];
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
        $course = Course::findOrFail($id)->get();

        $this->authorize("view", $course);

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
        $course = Course::findOrFail($id)->get()->get();

        $this->authorize("update", $course);

        return view("courses.edit")->with("course", $course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, $id)
    {
        // Check if course exists
        $course = Course::findOrFail($id)->get()->get();

        $this->authorize("update", $course);

        // Validate Request
        $validated = $request->validated();

        // Update course
        $course->code = $validated["code"];
        $course->name = $validated["name"];
        $course->ects = $validated["ects"];
        $course->type = $validated["type"];
        $course->description = $validated["description"];
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
        $course = Course::findOrFail($id)->get();

        $this->authorize("destroy", $course);

        // Delete course
        $course->delete();

        return redirect("/teachers/courses")->with("success", "Course Deleted");
    }
}
