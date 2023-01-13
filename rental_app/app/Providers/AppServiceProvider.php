<?php

declare(strict_types=1);

namespace App\Providers;

use App\Common\Rules\WorkDayRule;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            'App\Repository\CustomCarRepositoryInterface',
            'App\Repository\CustomCarRepository'
        );

        $this->app->bind(
            'App\Repository\CustomRentalRepositoryInterface',
            'App\Repository\CustomRentalRepository'
        );

        $this->app->bind(
            'App\Module\Cache\Serializer\CacheSerializerInterface',
            'App\Module\Cache\Serializer\ArrayCacheSerializer'
        );

        $this->app->when('App\Module\Report\Load\Cars\Handler')
            ->needs('App\Module\Cache\Strategy\RedisCacheInterfaceStrategy')
            ->give('App\Module\Cache\Strategy\RedisStrategySerializeCacheStrategy');
    }

    public function boot(ValidatorFactory $validator): void
    {
        $validator->extend('workday', WorkDayRule::class);
    }
}
