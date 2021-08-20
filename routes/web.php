<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use \App\Http\Controllers\StudentsController;

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

//Route::get('/home', [HomeController::class, 'index'])->name('home');
Auth::routes();
Route::get('/', [PagesController::class, "home"])->name("index");
Route::get('/home', [PagesController::class, "home"])->name("home");
Route::resource("/students",StudentsController::class);
