<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\TripScheduleController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\TicketController;

// Open Routes (Public Routes)

// Route::get('/dash', function () {
//     return view('backend/dashboard');
// });

Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);


// Route::group(['prefix' => 'user'], function () {
//     Route::get('trips', [TripController::class, 'findTrips']); // Find trips based on criteria
//     Route::get('tickets/available', [TicketController::class, 'availableTickets']); // View available tickets
//     Route::post('tickets/purchase', [TicketController::class, 'purchase']); // Purchase a ticket
// });

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', function () {
    return view('auth.register'); // Point to Blade view
})->name('register');

Route::get('/login', function () {
    return view('auth.login'); // Point to Blade view
})->name('login');


// Protected Routes (Authenticated users only)
Route::middleware(['auth:api'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', function () {
        return view('user.dashboard'); // User dashboard Blade view
    })->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // Admin dashboard Blade view
        })->name('admin.dashboard');

        // Vehicles and Vehicle Types
        Route::get('/vehicle-types', [VehicleTypeController::class, 'index'])->name('admin.vehicle-types.index');
        Route::get('/vehicles', [VehicleController::class, 'index'])->name('admin.vehicles.index');

        // Trips and Schedules
        Route::get('/trip-schedules', [TripScheduleController::class, 'index'])->name('admin.trip-schedules.index');
        Route::get('/trips', [TripController::class, 'index'])->name('admin.trips.index');

        // Tickets
        Route::get('/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

