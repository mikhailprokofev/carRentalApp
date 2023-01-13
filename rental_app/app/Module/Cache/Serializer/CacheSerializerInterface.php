<?php

namespace App\Module\Cache\Serializer;

interface CacheSerializerInterface
{
    public function serialize(mixed $data, string $keyConcatChar = '.', ?string $keyParent = null): mixed;

    public function deserialize(array $data, string $keyIndexDash = '.', string $keyConcatChar = '.'): mixed;
}
