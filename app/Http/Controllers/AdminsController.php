<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all users except the authenticated one
        $users = User::where("id", "!=", auth()->user()->id)->orderBy(
            "created_at", "DESC")->get();
        return view("admins.index")->with("users", $users);
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
    public function store(AdminStoreRequest $request)
    {
        // Validate Request
        $validated = $request->validated();

        // Create New User
        $user = new User();
        $user->name = $validated["name"];
        $user->role = "ADMIN";
        $user->email = $validated["email"];
        $user->password = Hash::make($validated["password"]);
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

        // Create New Admin
        $admin = new Admin();
        $admin->id = $user->id;
        $admin->name = $validated["name"];
        $admin->email = $validated["email"];
        $admin->image = $fileNameToStore;
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
        // Check if admin exists
        $admin = Admin::findOrFail($id);

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
        // Check if admin exists
        $admin = Admin::findOrFail($id);

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
    public function update(AdminUpdateRequest $request, $id)
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

        // Check if admin exists
        $admin = Admin::findOrFail($id);

        // Update admin
        $admin->name = $validated["name"];

        // Update image if image selected
        if($request->hasFile("image")) {
            Storage::delete("public/images/".$admin->image);
            $admin->image = $fileNameToStore;
        }

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
        // Check if user exists
        $user = User::findOrFail($id);

        if(auth()->user()->role === "ADMIN" && $user->role === "ADMIN" && $user->id !== auth()->user()->id) {
            return redirect("/admins")->with("error", "You cannot delete other admins");
        } elseif(auth()->user()->role !== "ADMIN") {
            return redirect("/")->with("error", "You cannot delete other users's accounts");
        }

        // Check if admin exists
        $admin = Admin::findOrFail($id);

        // Delete admin's image if default not selected
        if($admin->image !== "noimage.jpg") {
            Storage::delete("public/images/".$admin->image);
        }

        // Log out and delete account
        $admin->delete();
        auth()->logout();
        $user->delete();

        return redirect("/login")->with("success", "Account Deleted");
    }
}
