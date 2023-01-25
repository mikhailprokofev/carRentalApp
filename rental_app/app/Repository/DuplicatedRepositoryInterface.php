<?php

declare(strict_types=1);

namespace App\Repository;

interface DuplicatedRepositoryInterface
{
    public function findDuplicateValues(array $values): array;
}
