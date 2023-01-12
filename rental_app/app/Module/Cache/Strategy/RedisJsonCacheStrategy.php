<?php

declare(strict_types=1);

namespace App\Module\Cache\Strategy;

use Illuminate\Support\Facades\Redis;

final class RedisJsonCacheStrategy implements RedisCacheInterfaceStrategy
{
    public function getCacheValue(string $key): mixed
    {
        $data = Redis::hgetall($key);

        return $data ? json_decode(array_pop($data), true) : null;

    }

    public function setCacheValue(string $key, mixed $data, int $expire): void
    {
        Redis::hmset($key, [json_encode($data)]);
        Redis::expire($key, $expire);
    }
}
