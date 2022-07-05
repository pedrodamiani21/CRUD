<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    CustomerRepositoryInterface,
    ProductRepositoryInterface,
    OrderRepositoryInterface
 };

use App\Repositories\{
    CustomerRepository,
    ProductRepository,
    OrderRepository
 };

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CustomerRepositoryInterface::class,
            CustomerRepository::class,
            
        );
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class,

        );
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class,

        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
