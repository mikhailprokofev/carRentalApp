<?php

namespace App\Module\Cache\Strategy;

interface RedisCacheInterfaceStrategy
{
    public function getCacheValue(string $key): mixed;

    public function setCacheValue(string $key, mixed $data, int $expire): void;
}