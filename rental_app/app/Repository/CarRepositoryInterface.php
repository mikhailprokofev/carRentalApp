<?php

declare(strict_types=1);

namespace App\Repository;

interface CarRepositoryInterface
{
    public function findDuplicateValues(array $values): array;

    public function insert(array $data): void;
}
