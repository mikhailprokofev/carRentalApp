<?php

declare(strict_types=1);

namespace App\Module\Cache\Strategy;

use Illuminate\Support\Facades\Cache;

final class RedisPureLaravelCacheStrategy
{
    public function getCacheValue(string $key): mixed
    {
        // TODO: подумать над добавлением тегов универсально
        return Cache::has($key) ? Cache::tags('report_load')->get($key) : null;
    }

    public function setCacheValue(string $key, mixed $data, int $expire): void
    {
//        Cache::remember('report_load', $this->timeStore, function () use ($data) {
//            return $data;
//        });

        // TODO: подумать над добавлением тегов универсально
        Cache::tags('report_load')->put($key, $data, $expire);
    }
}
