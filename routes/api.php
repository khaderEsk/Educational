<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileTeacherController;
use App\Http\Controllers\ProfileStudentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::group(['prefix' => 'profile_teacher'], function () {
    Route::post('store', [ProfileTeacherController::class, 'store'])->middleware('jwt.verify');
    Route::post('update', [ProfileTeacherController::class, 'update'])->middleware('jwt.verify');
});

Route::group(['prefix' => 'profile_student'], function () {
    Route::post('store', [ProfileStudentController::class, 'store'])->middleware('jwt.verify');
    Route::post('update', [ProfileStudentController::class, 'update'])->middleware('jwt.verify');
});
