<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\TripScheduleController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\TicketController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

//Open Routes
Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);

Route::group(['prefix' => 'user'], function () {
    Route::get('buses', [VehicleController::class, 'index']); // View buses
    Route::get('tickets/available', [TicketController::class, 'availableTickets']); // View available tickets
    Route::post('tickets/purchase', [TicketController::class, 'purchase']); // Purchase a ticket
});


// Protected Routes
Route::group(['middleware' => 'auth:api'], function () {
    // Profile Management
    Route::get("profile", [AuthController::class, "profile"]);
    Route::get("logout", [AuthController::class, "logout"]);

    // Admin Routes
    Route::group(['prefix' => 'admin'], function () {
        Route::apiResource('vehicle-types', VehicleTypeController::class);
        Route::apiResource('buses', VehicleController::class);
        Route::apiResource('trip-schedules', TripScheduleController::class);
        Route::apiResource('trips', TripController::class); // Ensure logic differs if same controller
        Route::apiResource('tickets', TicketController::class);
        Route::get('tickets/available', [TicketController::class, 'availableTickets']);
    });
});