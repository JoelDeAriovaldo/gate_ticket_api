<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\TicketController;

/**
 * API Routes
 *
 * Defines all API endpoints, grouped by version and middleware.
 */

// API Version 1
Route::prefix('v1')->group(function () {
    // Public authentication routes
    Route::post('login', [AuthController::class, 'login'])->name('api.v1.login');
    Route::post('register', [AuthController::class, 'register'])->name('api.v1.register');

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Authenticated user actions
        Route::post('logout', [AuthController::class, 'logout'])->name('api.v1.logout');
        Route::get('profile', [AuthController::class, 'profile'])->name('api.v1.profile');

        // User management (admin only, handled by RoleMiddleware in controller)
        Route::apiResource('users', UserController::class);

        // Ticket management
        Route::apiResource('tickets', TicketController::class);

        // Custom ticket validation endpoint
        Route::post('tickets/{ticket}/validate', [TicketController::class, 'validateTicket'])
            ->name('api.v1.tickets.validate');
    });
});
