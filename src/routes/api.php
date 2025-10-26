<?php

use App\Http\Controllers\Api\Admin\NotificationController;
use App\Http\Controllers\Api\Admin\StatisticsController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RentController;
use App\Models\Notification;
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
    Route::resource('rents',RentController::class);
});

Route::prefix('statistika')->middleware('auth:sanctum')->group(function(){
    Route::get('/students/filter', [StatisticsController::class, 'filterStudents']);
    Route::get('/rents/filter', [StatisticsController::class, 'filterRents']);
    Route::get('/charts/student-rent-prices', [StatisticsController::class, 'studentRentPriceChart']);
});

Route::post('/notification/commit', [NotificationController::class,'commit']);

Route::prefix('/notification')->middleware('auth:sanctum')->group(function(){
    Route::get('commits', [NotificationController::class,'getCommits']);
    Route::get('mark-as-read/{id}', [NotificationController::class,'markAsRead']);
    Route::get('pending-rents', [NotificationController::class,'getPendingRents']);
    Route::get('rent-rejected/{id}', [NotificationController::class,'updateStatus']);
});
