<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;

interface CustomRentalRepositoryInterface
{
    public function insert(array $data): void;

    public function update(array $data): void;

    public function truncate(): void;

    public function findLoadCarsInfo(int $year, int $month): Collection;
}
