<?php

use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\UserSession;
use App\Http\Controllers\AccommodationsController;
use App\Http\Controllers\ContractsController;
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


// Public routes
// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::post('/logout', [AuthController::class, 'logout']);
Route::prefix('accommodations')->group(function () {
    Route::get('/', [AccommodationsController::class, 'index']);
    Route::get('/{id}', [AccommodationsController::class, 'show']);
    Route::post('/', [AccommodationsController::class, 'store']);
    Route::put('/{id}', [AccommodationsController::class, 'update']);
    Route::delete('/{id}', [AccommodationsController::class, 'destroy']);
});
Route::prefix('contracts')->group(function () {
    Route::get('/', [ContractsController::class, 'index']);
    Route::get('/{id}', [ContractsController::class, 'show']);
    Route::post('/', [ContractsController::class, 'store']);
    Route::put('/{id}', [ContractsController::class, 'update']);
    Route::delete('/{id}', [ContractsController::class, 'destroy']);
});
Route::prefix('booking')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::get('/{id}', [BookingController::class, 'show']);
    Route::post('/', [BookingController::class, 'store']);
    Route::put('/{id}', [BookingController::class, 'update']);
    Route::delete('/{id}', [BookingController::class, 'destroy']);
});




