<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TeachersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', [PagesController::class, "index"])->name("index");
Route::resource("/admins",AdminsController::class);
Route::resource("/students",StudentsController::class);
Route::resource("/teachers",TeachersController::class);

