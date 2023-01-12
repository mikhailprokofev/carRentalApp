<?php

declare(strict_types=1);

namespace App\Module\Cache\Strategy;

use App\Module\Cache\Serializer\CacheSerializerInterface;
use Illuminate\Support\Facades\Redis;

final class RedisStrategySerializeCacheStrategy implements RedisCacheInterfaceStrategy
{
    public function __construct(
        private CacheSerializerInterface $cacheSerializator,
    ) {}

    public function getCacheValue(string $key): mixed
    {
        $data = Redis::hgetall($key);

        return $data ? $this->cacheSerializator->deserialize($data) : null;
    }

    public function setCacheValue(string $key, mixed $data, int $expire): void
    {
        Redis::hmset($key, $this->cacheSerializator->serialize($data));
        Redis::expire($key, $expire);
    }
}
