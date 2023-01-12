<?php

declare(strict_types=1);

namespace App\Module\Report\Load\CacheEntity;

final class Report
{
    public function __construct(
        private array $data,
    ) {}

    public function __serialize(): array
    {
        return $this->serialize($this->data);
    }

    public function serialize(array $data, ?string $keyParent = null, ?string $keyConcatChar = '.'): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $keyResult = $keyParent ? $keyParent . $keyConcatChar . $key : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->serialize($value, $keyResult, $keyConcatChar));
            } else {
                $result[$keyResult] = $value;
            }
        }

        return $result;
    }

    public function unserialize(array $data, ?string $keyIndexDirt = '.', ?string $keyConcatChar = '.'): array
    {
        foreach ($data as $key => $value) {
            $keys = explode($keyConcatChar, $key);

            $nodes[] = $this->makeArrayFromKeys($keys, $value, $keyIndexDirt);
        }

        $result = $nodes ? array_merge_recursive(...$nodes) : [];

        return $this->deleteDirtFromKey($result);
    }

    private function makeArrayFromKeys(array $keys, string $value, string $keyIndexDirt)
    {
        $key = array_shift($keys);
        $result[is_numeric($key) ? $keyIndexDirt . $key : $key] = count($keys)
            ? $this->makeArrayFromKeys($keys, $value, $keyIndexDirt)
            : $value;

        return $result;
    }

    private function deleteDirtFromKey(array $data, ?string $keyIndexDirt = '.'): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $keyResult = str_starts_with($key, $keyIndexDirt) ? substr($key, 1) : $key;

            $result[$keyResult] = is_array($value) ? $this->deleteDirtFromKey($value, $keyIndexDirt) : $value;
        }

        return $result;
    }
}
