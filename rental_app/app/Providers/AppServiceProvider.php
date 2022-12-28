<?php

namespace App\Providers;

use App\Common\Rules\WorkDayRule;
use App\Repository\CarRepository;
use App\Repository\CarRepositoryInterface;
use App\Repository\RentalRepository;
use App\Repository\RentalRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;

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
    public function boot(ValidatorFactory $validator): void
    {
        $validator->extend('workday', WorkDayRule::class);
    }
}
