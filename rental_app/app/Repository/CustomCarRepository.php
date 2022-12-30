<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\UuidInterface;

final class CustomCarRepository implements CustomCarRepositoryInterface
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

    private function subQueryFindAffordable(string $start, string $end, int $restDays = 3): Builder
    {
        return DB::table('cars', 'c')
            ->select('c.id as id')
            ->leftJoin('rentals', 'c.id', '=', 'rentals.car_id')
            ->whereRaw("isempty(daterange('$start', '$end') * daterange(rentals.rental_start, rentals.rental_end + $restDays))")
            ->orWhereNull('rentals.id')
            ->groupBy('c.id');
    }

    public function findAffordableCarById(UuidInterface $carId, string $start, string $end): Collection
    {
        $subQb = $this->subQueryFindAffordable($start, $end);

        $subQb
            ->where('c.id', $carId);

        return $subQb->get();
    }

    public function findAffordableCars(string $start, string $end): Collection
    {
        $subQb = $this->subQueryFindAffordable($start, $end);

        return $subQb->get();
    }
}
