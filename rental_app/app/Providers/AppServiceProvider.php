<?php

namespace App\Providers;

use App\Common\Rules\WorkDayRule;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
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
