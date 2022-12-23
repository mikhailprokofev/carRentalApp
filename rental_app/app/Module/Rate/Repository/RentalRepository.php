<?php

declare(strict_types=1);

namespace App\Module\Rate\Repository;

use Illuminate\Support\Facades\DB;

final class RentalRepository implements RentalRepositoryInterface
{
    public function findDuplicateValues(array $values): array
    {
        // TODO: что является уникальным ключом для таблицы rental
//        return DB::table('rentals')
//            ->select('rental_start', 'rental_end', 'cars_id')
//            ->whereIn('number_plate', $values)
//            ->get()
//            ->map(fn ($car) => $car->number_plate)
//            ->toArray();
    }

    public function insert(array $data): void
    {
        if (count($data)) {
            DB::table('rentals')->insert($data);
        }
    }

    public function update(array $data): void
    {
        if (count($data)) {
//            DB::table('rentals')->upsert($data);
        }
    }
}
