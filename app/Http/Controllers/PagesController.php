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
        return view("pages.index");
    }

    public function home()
    {
        return view("pages.home");
    }

    public function about() {
        return view("pages.about");
    }
}
