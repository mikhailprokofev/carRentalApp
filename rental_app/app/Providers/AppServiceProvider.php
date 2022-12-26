<?php

namespace App\Providers;

use App\Module\Rate\Repository\CarRepository;
use App\Module\Rate\Repository\CarRepositoryInterface;
use App\Module\Rate\Repository\RentalRepository;
use App\Module\Rate\Repository\RentalRepositoryInterface;
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
        $this->app->instance(CarRepositoryInterface::class, CarRepository::class);
        $this->app->instance(RentalRepositoryInterface::class, RentalRepository::class);
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
