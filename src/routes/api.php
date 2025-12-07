<?php

use App\Http\Controllers\Api\Admin\AdminProfileController;
use App\Http\Controllers\Api\Admin\NotificationController;
use App\Http\Controllers\Api\Admin\StatisticsController;
use App\Http\Controllers\Api\Admin\StudentController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\XmlDownloadController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RentController;
use Illuminate\Support\Facades\Route;

// auth 
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// students | users | renter | logout
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('renters', UserController::class);
    Route::apiResource('admin', UserController::class);
    Route::apiResource('rents',RentController::class);
    Route::apiResource('admins', AdminProfileController::class);
    Route::get('admins/profile', [AdminProfileController::class, 'show']);
    Route::get('download-xml/{model}', [XmlDownloadController::class, 'export']);
});

Route::prefix('statistika')
->middleware(['auth:sanctum'])
->group(function(){
    Route::get('/', [StatisticsController::class, 'index']);
    Route::get('/students/filter', [StatisticsController::class, 'filterStudents']);
    Route::get('/rents/filter', [StatisticsController::class, 'filterRents']);
    Route::get('/charts/student-rent-prices', [StatisticsController::class, 'studentRentPriceChart']);
});





Route::prefix('/notification')->middleware('auth:sanctum')->group(function(){
    Route::get('commits', [NotificationController::class,'getCommits']);
    Route::get('mark-as-read/{id}', [NotificationController::class,'markAsRead']);
    Route::get('pending-rents', [NotificationController::class,'getPendingRents']);
    Route::get('rent-update-status/{id}/{status}', [NotificationController::class,'updateStatus']);
});

Route::post('/notification/commit', [NotificationController::class,'commit']);



