<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\TripScheduleController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\TicketController;

// Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// User Routes (protected with `auth:api` and `role:user`)
Route::group(['prefix' => 'user', 'middleware' => ['auth:api', RoleMiddleware::class . ':user']], function () {
    Route::get('vehicle', [VehicleController::class, 'index']);
    Route::get('tickets/available', [TicketController::class, 'availableTickets']);
    Route::post('tickets/purchase', [TicketController::class, 'purchase']);
});

// Admin Routes (protected with `auth:api` and `role:admin`)
Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', RoleMiddleware::class . ':admin']], function () {
    Route::apiResource('vehicle-types', VehicleTypeController::class);
    Route::apiResource('vehicle', VehicleController::class);
    Route::apiResource('trip-schedules', TripScheduleController::class);
    Route::apiResource('trips', TripController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::get('tickets/available', [TicketController::class, 'availableTickets']);
});

// Authenticated Routes (common for all roles)
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});