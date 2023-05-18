<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Auth
Route::post('/login', [Controllers\Api\AuthApiController::class, 'login']);
Route::post('/logout', [Controllers\Api\AuthApiController::class, 'logout'])->middleware('auth:sanctum');

//Lokasi
Route::get('/get-lokasi', [Controllers\Api\LokasiApiController::class, 'index'])->middleware('auth:sanctum');
Route::post('/add-lokasi', [Controllers\Api\LokasiApiController::class, 'store'])->middleware('auth:sanctum');
Route::put('/update-lokasi', [Controllers\Api\LokasiApiController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/delete-lokasi', [Controllers\Api\LokasiApiController::class, 'destroy'])->middleware('auth:sanctum');
