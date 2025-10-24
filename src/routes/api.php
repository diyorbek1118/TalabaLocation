<?php

use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// auth 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// students | users | renter
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('students', StudentController::class);
    Route::resource('renters', UserController::class);
    Route::resource('admins', UserController::class);
});

