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
