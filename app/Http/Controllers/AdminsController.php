<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admins.index")->with("users", User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admins.create");
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
        $user->role = "ADMIN";
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->save();

        $admin = new Admin();
        $admin->id = $user->id;
        $admin->name = $request->input("name");
        $admin->save();

        return redirect("/admins")->with("success", "Admin Created");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::find($id);

        if(auth()->user()->role !== "ADMIN") {
            return redirect("/")->with("error", "You cannot view other users's profiles");
        }

        return view("admins.show")->with("admin", $admin);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::find($id);

        if($admin->id !== auth()->user()->id) {
            return redirect("/")->with("error", "You cannot edit other users's profiles");
        }

        return view("admins.edit")->with("admin", $admin);
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

        $admin = Admin::find($id);
        $admin->name = $request->input("name");
        $admin->save();

        return redirect("/admins/$id")->with("success", "Profile Updated");
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

        if(auth()->user()->role === "ADMIN" && $user->role === "ADMIN" && $user->id !== auth()->user()->id) {
            return redirect("/admins")->with("error", "You cannot delete other admins");
        } elseif(auth()->user()->role !== "ADMIN") {
            return redirect("/")->with("error", "You cannot delete other users's accounts");
        }

        $user->delete();

        $admin = Admin::find($id);
        $admin->delete();

        return redirect("/login")->with("success", "Account Deleted");
    }
}
