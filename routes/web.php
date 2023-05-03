<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\Autentikasi\AutentikasiController;
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
    return view('auth.login');
});


Route::group(["middleware" => ["guest"]], function() {
    Route::get("/login", [AutentikasiController::class, "login"]);
    Route::post("/login", [AutentikasiController::class, "post_login"]);
    Route::get("/register", [AutentikasiController::class, "register"]);
    Route::post("/register", [AutentikasiController::class, "post_register"]);

});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/dashboard', function(){return view('dashboard');});
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // ROUTE USER
    Route::get('/user/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/tambah', [UserController::class, 'tambah'])->name('user_tambah');
    Route::post('/user/store', [UserController::class, 'store'])->name('user_tambah');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user_edit');
    Route::post('/user/update', [UserController::class, 'update'])->name('user_edit');
    Route::get('/user/hapus/{id}', [UserController::class, 'hapus'])->name('user');

     // ROUTE DRIVER
    Route::get('/driver/driver', [DriverController::class, 'index'])->name('driver');
    Route::get('/driver/tambah', [DriverController::class, 'tambah']);
    Route::post('/driver/store', [DriverController::class, 'store']);
    Route::get('/driver/edit/{id}', [DriverController::class, 'edit']);
    Route::post('/driver/update', [DriverController::class, 'update']);
    Route::get('/driver/hapus/{id}', [DriverController::class, 'hapus']);

    // ROUTE CRUD LOKASI
    Route::get('/lokasi/lokasi', [LokasiController::class, 'index'])->name('lokasi');
    Route::get('/lokasi/tambah', [LokasiController::class, 'tambah']);
    Route::post('/lokasi/store', [LokasiController::class, 'store']);
    Route::get('/lokasi/edit/{id}', [LokasiController::class, 'edit']);
    Route::post('/lokasi/update', [LokasiController::class, 'update']);
    Route::get('/lokasi/hapus/{id}', [LokasiController::class, 'hapus']);

    // ROUTE MAPS
    Route::get('/maps/maps', [MapsController::class, 'index'])->name('maps');
});


// Auth::routes();

Route::group(["middleware" => ["cek_login"]], function() {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);

    Route::post("/logout", [AutentikasiController::class, "logout"]);
});
