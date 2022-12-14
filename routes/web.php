<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAccess;
use App\Http\Controllers\GroupAccess;
use App\Http\Controllers\MenuAccess;
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
    return redirect()->route('view_login');
});

    Route::get('/viewlogin', [AuthController::class, 'viewlogin'])->name('view_login');
 	Route::post('/login', [AuthController::class, 'loginaction'])->name('login');
    Route::post('/register', [AuthController::class, 'registeraction'])->name('register');
    Route::get('/viewreg', [AuthController::class, 'viewregister'])->name('view_register');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/useraccess', [UserAccess::class, 'index'])->name('useraccess');
    Route::post('/getgroupaccess', [UserAccess::class, 'getgroupaccess'])->name('getgroupakses');
    Route::get('/datatableuser', [UserAccess::class, 'create'])->name('datatableuser');
    Route::post('/edituser', [UserAccess::class, 'edit'])->name('edituser');
    Route::post('/updateuser', [UserAccess::class, 'update'])->name('updateuser');   
    Route::post('/saveuser', [UserAccess::class, 'store'])->name('saveuser'); 
    Route::get('/deleteuser/{id}', [UserAccess::class, 'destroy']);

    Route::get('/groupaccess', [GroupAccess::class, 'index'])->name('groupaccess');
    Route::get('/datatablegroup', [GroupAccess::class, 'datatable'])->name('datatablegroup');
    Route::post('/getgroupmenu', [GroupAccess::class, 'getgroupmenu'])->name('getgroupmenu');
    Route::post('/editgroup', [GroupAccess::class, 'edit'])->name('editgroup');
    Route::post('/updategroup', [GroupAccess::class, 'update'])->name('updategroup');   
    Route::post('/savegroup', [GroupAccess::class, 'store'])->name('savegroup'); 
    Route::get('/deletegroup/{id}', [GroupAccess::class, 'destroy']);

    Route::get('/menuaccess', [MenuAccess::class, 'index'])->name('menuaccess');
    Route::get('/datatablemenu', [MenuAccess::class, 'showdatatable'])->name('datatablemenu');
    Route::post('/editmenu', [MenuAccess::class, 'edit'])->name('editmenu');
    Route::post('/updatemenu', [MenuAccess::class, 'update'])->name('updatemenu');   
    Route::post('/savemenu', [MenuAccess::class, 'store'])->name('savemenu'); 
    Route::get('/deletemenu/{id}', [MenuAccess::class, 'destroy']);
    Route::get('/activemenu/{id}', [MenuAccess::class, 'active']);

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
