<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Public routes (no authentication required)
    Route::post('/login', [AuthController::class, 'login'])->name('api.v1.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.v1.register');

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.v1.logout');
        Route::get('/profile', [AuthController::class, 'profile'])->name('api.v1.profile');

        // Ticket routes
        Route::apiResource('tickets', TicketController::class);

        // Custom ticket endpoints
        Route::post('/tickets/{ticket}/validate', [TicketController::class, 'validateTicket'])
            ->name('api.v1.tickets.validate');
        Route::get('/tickets-statistics', [TicketController::class, 'statistics'])
            ->name('api.v1.tickets.statistics');

        // User routes (admin only or self-management)
        Route::apiResource('users', UserController::class);
    });
});
