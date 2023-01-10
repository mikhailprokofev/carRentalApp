<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface CustomCarRepositoryInterface
{
    public function findDuplicateValues(array $values): array;

    public function insert(array $data): void;

    public function findAvailableCarById(UuidInterface $carId, string $start, string $end): Collection;

    public function findAvailableCars(string $start, string $end): Collection;

    public function findAvailableCarByNumberPlate(
        string $numberPlate,
        string $start,
        string $end,
        int $restDays = 4
    ): Collection;

    public function isExistByNumberPlate(string $numberPlate): bool;
}
