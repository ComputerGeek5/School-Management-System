<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function index()
    {
        $error = null;

        if (auth()->user()->role === "ADMIN") {
            // Check for any session errors
            if(session("error")) {
                $error = session("error");
                return redirect()->route("admins.search")->with("error", $error);
            }

            return redirect()->route("admins.search");
        } elseif (auth()->user()->role === "Student") {
            // Check for any session errors
            if(session("error")) {
                $error = session("error");
                return redirect()->route("students.index")->with("error", $error);
            }

            return redirect()->route("students.index");
        } else {
            // Check for any session errors
            if(session("error")) {
                $error = session("error");
                return redirect()->route("teachers.index")->with("error", $error);
            }

            return redirect()->route("teachers.index");
        }
    }

    public function create() {

        return view("pages.create");
    }
}
