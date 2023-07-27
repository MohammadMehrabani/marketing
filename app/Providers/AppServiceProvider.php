<?php

namespace App\Providers;

use App\Contracts\UserAuthenticateServiceInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\MysqlUserRepository;
use App\Services\UserAuthenticateService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->singleton(UserRepositoryInterface::class, MysqlUserRepository::class);

        // Services
        $this->app->singleton(UserAuthenticateServiceInterface::class, UserAuthenticateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data = [], $statusCode = 200) {
            return Response::json([
                'success' => true,
                'data' => $data
            ], $statusCode);
        });

        Response::macro('error', function ($data = [], $statusCode = 400) {
            return Response::json([
                'success' => false,
                'errors' => $data
            ], $statusCode);
        });
    }
}
