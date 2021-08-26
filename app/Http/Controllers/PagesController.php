<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function index()
    {
        $error = null;

        if (auth()->user()->role === "ADMIN") {
            if(session("error")) {
                $error = session("error");
                return redirect()->route("admins.index")->with("error", $error);
            }

            return redirect()->route("admins.index");
        } elseif (auth()->user()->role === "Student") {
            if(session("error")) {
                $error = session("error");
                return redirect()->route("students.index")->with("error", $error);
            }

            return redirect()->route("students.index");
        } else {
            if(session("error")) {
                $error = session("error");
                return redirect()->route("teachers.index")->with("error", $error);
            }

            return redirect()->route("teachers.index");
        }
    }

//    public function about()
//    {
//        return view("pages.about");
//    }

    public function create() {
        return view("pages.create");
    }
}
