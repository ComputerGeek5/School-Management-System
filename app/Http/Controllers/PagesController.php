<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === "ADMIN") {
            return redirect()->route("admins.index");
        } elseif (auth()->user()->role === "Student") {
            return redirect()->route("students.index");
        } else {
            return redirect()->route("teachers.index");
        }
    }

    public function about()
    {
        return view("pages.about");
    }

    public function create() {
        return view("pages.create");
    }
}
