<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface CarRepositoryInterface
{
    public function findDuplicateValues(array $values): array;

    public function insert(array $data): void;

    public function findAffordableCarById(UuidInterface $carId, string $start, string $end): Collection;

    public function findAffordableCars(string $start, string $end): Collection;
}
