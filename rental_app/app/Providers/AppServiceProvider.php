<?php

namespace App\Providers;

use App\Repository\CarRepository;
use App\Repository\CarRepositoryInterface;
use App\Repository\RentalRepository;
use App\Repository\RentalRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->instance(CarRepositoryInterface::class, CarRepository::class);
        $this->app->instance(RentalRepositoryInterface::class, RentalRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
