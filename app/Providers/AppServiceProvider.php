<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\TicketService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind services for dependency injection
        $this->app->singleton(AuthService::class, fn ($app) => new AuthService());
        $this->app->singleton(UserService::class, fn ($app) => new UserService());
        $this->app->singleton(TicketService::class, fn ($app) => new TicketService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
