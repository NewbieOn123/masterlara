<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
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
    // return view('dashboard');
    return view('layouts.auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/login', [AuthController::class, 'loginaction'])->name('login');
    Route::post('/register', [AuthController::class, 'registeraction'])->name('register');

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/viewlogin', [AuthController::class, 'viewlogin'])->name('view_login');
    Route::get('/viewreg', [AuthController::class, 'viewregister'])->name('view_register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
