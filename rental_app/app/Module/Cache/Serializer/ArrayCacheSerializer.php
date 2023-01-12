<?php

declare(strict_types=1);

namespace App\Module\Cache\Serializer;

final class ArrayCacheSerializer implements CacheSerializerInterface
{
    public function serialize(mixed $data, string $keyConcatChar = '.', ?string $keyParent = null): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $keyResult = $keyParent ? $keyParent . $keyConcatChar . $key : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->serialize($value, $keyConcatChar, $keyResult));
            } else {
                $result[$keyResult] = $value;
            }
        }

        return $result;
    }

    public function deserialize(array $data, string $keyIndexDash = '.', string $keyConcatChar = '.'): array
    {
        foreach ($data as $key => $value) {
            $keys = explode($keyConcatChar, $key);

            $nodes[] = $this->makeArray($keys, $value, $keyIndexDash);
        }

        $result = $nodes ? array_merge_recursive(...$nodes) : [];

        return $this->deleteDashFromKey($result);
    }

    private function makeArray(array $keys, string $value, string $keyIndexDash): array
    {
        $key = array_shift($keys);

        $result[is_numeric($key) ? $keyIndexDash . $key : $key] = count($keys)
            ? $this->makeArray($keys, $value, $keyIndexDash)
            : $value;

        return $result;
    }

    private function deleteDashFromKey(array $data, ?string $keyIndexDash = '.'): array
    {
        foreach ($data as $key => $value) {
            $keyResult = str_starts_with($key, $keyIndexDash) ? substr($key, 1) : $key;

            $result[$keyResult] = is_array($value) ? $this->deleteDashFromKey($value, $keyIndexDash) : $value;
        }

        return $result ?? [];
    }
}
