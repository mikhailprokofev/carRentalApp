<?php

declare(strict_types=1);

namespace App\Module\Rate\Repository;

use Illuminate\Support\Facades\DB;

final class CarRepository implements CarRepositoryInterface
{
    public function findDuplicateValues(array $values): array
    {
        return DB::table('cars')
            ->select('number_plate')
            ->whereIn('number_plate', $values)
            ->get()
            ->map(fn ($car) => $car->number_plate)
            ->toArray();
    }

    public function insert(array $data): void
    {
        if (count($data)) {
            DB::table('cars')->insert($data);
        }
    }
}
