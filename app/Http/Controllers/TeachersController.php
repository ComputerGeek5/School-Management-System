<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("teachers.index");
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
            "name" => "required",
            "email" => "required|unique:users,email",
            "password" => "required|min:8"
        ]);

        $user = new User();
        $user->name = $request->input("name");
        $user->role = "Teacher";
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->save();

        $teacher = new Teacher();
        $teacher->id = $user->id;
        $teacher->name = $request->input("name");
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

        if(auth()->user()->role !== "ADMIN" && $teacher->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot view other users's profiles");
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
        ]);

        $user = User::find($id);

        if($user->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        $user->name = $request->input("name");
        $user->save();

        $teacher = Teacher::find($id);
        $teacher->name = $request->input("name");
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

        if(auth()->user()->role !== "ADMIN") {
            return redirect("/")->with("error", "You cannot delete other users's accounts");
        }

        $user->delete();

        $teacher = Teacher::find($id);
        $teacher->delete();

        if(auth()->user()->id !== $teacher->id) {
            return redirect("/admins")->with("success", "Teacher Deleted");
        }

        return redirect("/login")->with("success", "Account Deleted");
    }
}
