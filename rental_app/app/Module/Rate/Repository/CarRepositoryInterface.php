<?php

declare(strict_types=1);

namespace App\Module\Rate\Repository;

interface CarRepositoryInterface
{
    public function findDuplicateValues(array $values): array;

    public function insert(array $data): void;
}
