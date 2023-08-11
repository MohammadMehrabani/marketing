<?php

namespace App\Providers;

use App\Contracts\MarketerProductRepositoryInterface;
use App\Contracts\MarketerProductServiceInterface;
use App\Contracts\PasswordResetTokenRepositoryInterface;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ProductServiceInterface;
use App\Contracts\RedirectorServiceInterface;
use App\Contracts\UserAuthenticateServiceInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\MysqlMarketerProductRepository;
use App\Repositories\MysqlPasswordResetTokenRepository;
use App\Repositories\MysqlProductRepository;
use App\Repositories\MysqlUserRepository;
use App\Services\MarketerProductService;
use App\Services\ProductService;
use App\Services\RedirectorService;
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
        $singletons = [
            // Repositories
            UserRepositoryInterface::class               => MysqlUserRepository::class,
            ProductRepositoryInterface::class            => MysqlProductRepository::class,
            MarketerProductRepositoryInterface::class    => MysqlMarketerProductRepository::class,
            PasswordResetTokenRepositoryInterface::class => MysqlPasswordResetTokenRepository::class,
            // Services
            UserAuthenticateServiceInterface::class      => UserAuthenticateService::class,
            ProductServiceInterface::class               => ProductService::class,
            MarketerProductServiceInterface::class       => MarketerProductService::class,
            RedirectorServiceInterface::class            => RedirectorService::class,
        ];

        foreach ($singletons as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }
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
