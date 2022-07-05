<?php

namespace App\Providers;

use App\Repositories\Contracts\{
    CustomerRepositoryInterface,
    ProductRepositoryInterface
 };

use App\Repositories\{
    CustomerRepository,
    ProductRepository
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
