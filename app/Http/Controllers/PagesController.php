<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(auth()->user()->role === "ADMIN") {
            return redirect()->route("admins.index");
        } elseif(auth()->user()->role === "Student") {
            return redirect()->route("students.index");
        } else {
            return redirect()->route("teachers.index");
        }
    }

    public function about() {
        return view("pages.about");
    }
}
