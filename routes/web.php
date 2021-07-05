<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/bootstrap4', [\App\Http\Controllers\UsersController::class, 'bootstrap4']);
Route::get('/bootstrap5', [\App\Http\Controllers\UsersController::class, 'bootstrap5']);
Route::get('/tailwind', [\App\Http\Controllers\UsersController::class, 'tailwind']);
