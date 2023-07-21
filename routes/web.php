<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\JarakController;
use App\Http\Controllers\DriverLokasiController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\JenisKendaraanController;
use App\Http\Controllers\PSOController;

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


Route::group(["middleware" => ["guest"]], function () {
    Route::get("/login", [AutentikasiController::class, "login"]);
    Route::post("/login", [AutentikasiController::class, "post_login"]);
    Route::get("/register", [AutentikasiController::class, "register"]);
    Route::post("/register", [AutentikasiController::class, "post_register"]);

});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AutentikasiController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard'); });
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
    Route::get('/driver/edit/{id}', [DriverController::class, 'edit'])->name('driver_edit');
    Route::post('/driver/update', [DriverController::class, 'update']);
    Route::get('/driver/hapus/{id}', [DriverController::class, 'hapus']);
    Route::get('/driver/detail/{id}', [DriverController::class, 'detail'])->name('driver_detail');

    // ROUTE CRUD LOKASI
    Route::get('/lokasi/lokasi', [LokasiController::class, 'index'])->name('lokasi');
    Route::get('/lokasi/tambah', [LokasiController::class, 'tambah']);
    Route::post('/lokasi/store', [LokasiController::class, 'store']);
    Route::get('/lokasi/edit/{id}', [LokasiController::class, 'edit'])->name('lokasi.edit');
    Route::post('/lokasi/update', [LokasiController::class, 'update']);
    Route::get('/lokasi/hapus/{id}', [LokasiController::class, 'hapus']);

    // ROUTE MAPS
    Route::get('/maps/maps', [MapsController::class, 'index'])->name('maps');

    // ROUTE CRUD PROFIL
    Route::get('/profil/profil', [ProfilController::class, 'index'])->name('profil');

    // ROUTE JARAK
    Route::get('/jarak/jarak', [JarakController::class, 'index'])->name('jarak');
    Route::get('/jarak/detail/{id}', [JarakController::class, 'detail'])->name('jarak_detail');
    // Route::post('/jarak/create', [JarakController::class, 'create'])->name('jarak.create');

    // driver_lokasi
    Route::get('/driver_lokasi/driver_lokasi', [DriverLokasiController::class, 'index'])->name('driver_lokasi');
    Route::get('/driver_lokasi/tambah', [DriverLokasiController::class, 'tambah']);
    Route::post('/driver_lokasi/store', [DriverLokasiController::class, 'store']);
    Route::get('/driver_lokasi/edit/{id}', [DriverLokasiController::class, 'edit'])->name('driver_lokasi.edit');
    Route::post('/driver_lokasi/update', [DriverLokasiController::class, 'update']);
    Route::get('/driver_lokasi/hapus/{id}', [DriverLokasiController::class, 'hapus']);

    // JENIS KENDARAAN
    Route::get('/jenis_kendaraan/jenis_kendaraan', [JenisKendaraanController::class, 'index'])->name('jenis_kendaraan');
    Route::get('/jenis_kendaraan/tambah', [JenisKendaraanController::class, 'tambah']);
    Route::post('/jenis_kendaraan/store', [JenisKendaraanController::class, 'store']);
    Route::get('/jenis_kendaraan/edit/{id}', [JenisKendaraanController::class, 'edit'])->name('jenis_kendaraan.edit');
    Route::post('/jenis_kendaraan/update', [JenisKendaraanController::class, 'update']);
    Route::get('/jenis_kendaraan/hapus/{id}', [JenisKendaraanController::class, 'hapus']);

    // ROUTE RUTE

    Route::get('/rute/rute', [RuteController::class, 'index'])->name('rute');
    Route::get('/rute/detail/{id}', [RuteController::class, 'detail'])->name('rute_detail');
    // Route::get('/rute/rute_gmaps', [RuteController::class, 'index'])->name('rute');
    // Route::get('/rute/rute_gmaps/perdriver/{id}', [RuteController::class, 'detail'])->name('rute_gmaps.detail');
    // Route::post('/rute/rute_gmaps', [RuteController::class, 'calculateRoute'])->name('calculate-route');

    // ROUTE RUTE PSO
    Route::get('/rute/rute_pso', [PSOController::class, 'index'])->name('rute_pso');
    Route::get('/maps/data/{id}', [PSOController::class, 'data'])->name('maps.data');


});


// Auth::routes();

// Route::group(["middleware" => ["cek_login"]], function() {
//     Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);

//     Route::post("/logout", [AutentikasiController::class, "logout"]);
// });
