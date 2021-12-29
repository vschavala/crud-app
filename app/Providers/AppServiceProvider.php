<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\IProductRepository;
use App\Repository\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //regestering product interface and repository
        $this->app->bind(IProductRepository::class, ProductRepository::class);
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
