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
    public function index(Request $request){
        $this->authorize("viewAny", Admin::class);

        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the users table
        $users = User::query()
            ->where("id", "!=", auth()->user()->id)
            ->where('name', 'LIKE', "%{$search}%")
            ->simplePaginate(4);

        // Return the search view with the results
        return view('admins.index')->with("users", $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize("create", Admin::class);
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
        $this->authorize("create", Admin::class);

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
    public function show(Admin $admin)
    {
        $this->authorize("view", $admin);

        return view("admins.show")->with("admin", $admin);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $this->authorize("update", $admin);

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
        // Check if admin exists
        $admin = Admin::findOrFail($id)->get();

        $this->authorize("update", $admin);

        // Validate Request
        $validated = $request->validated();

        // Check if user exists
        $user = User::findOrFail($id)->get();

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
        $user = User::findOrFail($id)->get();

        // Check if admin exists
        $admin = Admin::findOrFail($id)->get();

        $this->authorize("delete", $admin);

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
