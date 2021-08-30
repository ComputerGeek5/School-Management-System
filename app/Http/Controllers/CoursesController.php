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
    public function store(CourseStoreRequest $request)
    {
        // Validate Request
        $validated = $request->validated();

        if(auth()->user()->role !== "Teacher") {
            return view("/")->with("error", "Only teachers can create courses");
        }

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
    public function update(CourseUpdateRequest $request, $id)
    {
        // Validate Request
        $validated = $request->validated();

        // Check if course exists
        $course = Course::findOrFail($id);

        if(auth()->user()->role !== "Teacher") {
            return view("/")->with("error", "Only teachers can edit courses");
        } elseif(auth()->user()->id !== $course->teacher_id) {
            return redirect("/teachers")->with("error", "You cannot edit other teachers's courses");
        }

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
