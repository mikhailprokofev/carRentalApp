<?php

namespace App\Providers;

use App\Common\Rules\WorkDayRule;
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
        //
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
