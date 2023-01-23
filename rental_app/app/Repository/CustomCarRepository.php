<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\EC\Curves\prime192v1;
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

    public function subQueryFindAffordable(string $start, string $end, int $restDays = 4): Builder
    {
        return DB::table('rentals', 'r')
            ->select('r.car_id')
            ->whereRaw("not isempty(daterange('$start', '$end') * daterange(r.rental_start - $restDays, r.rental_end + $restDays))")
            ->orWhereRaw("daterange(r.rental_start - $restDays, r.rental_end + $restDays) @> ('$start')::date")
            ->groupBy('r.car_id');
    }

    // TODO: optimize: duplicate in findAffordableCars
    public function findAvailableCarById(UuidInterface $carId, string $start, string $end, int $restDays = 4): Collection
    {
        $subQb = $this->subQueryFindAffordable($start, $end, $restDays);

        $qb = DB::table('cars', 'c')
            ->leftJoinSub($subQb, 'r', function ($join) {
                $join->on('c.id', '=', 'r.car_id');
            })
            ->whereNull('r.car_id')
            ->where('c.id', $carId);

        return $qb->get();
    }

    // TODO: optimize: duplicate in findAffordableCarById
    public function findAvailableCarByNumberPlate(
        string $numberPlate,
        string $start,
        string $end,
        int $restDays = 4
    ): Collection {
        $subQb = $this->subFindAvailableCars($start, $end, $restDays);

        $subQb
            ->where('c.number_plate', $numberPlate);

        return $subQb->get();
    }

    // TODO: optimize: duplicate in findAffordableCarById
    public function findAvailableCars(string $start, string $end, int $restDays = 4): Collection
    {
        $subQb = $this->subFindAvailableCars($start, $end, $restDays);

        return $subQb->get();
    }

    public function withFilters(string $start, string $end, ?int $min, ?int $max, int $restDays = 4): Collection
    {
        $subQb = $this->subFindAvailableCars($start, $end, $restDays);

        $qb = $subQb;
        if (!empty($min)) {
            $qb = $subQb->Where('c.base_salary', '>=', $min);
        }
        if (!empty($max)) {
            $qb = $subQb->Where('c.base_salary', '<=', $max);
        }

        return $qb->get();
    }

    private function subFindAvailableCars(string $start, string $end, int $restDays = 4): Builder
    {
        $subQb = $this->subQueryFindAffordable($start, $end, $restDays);

        $qb = DB::table('cars', 'c')
            ->leftJoinSub($subQb, 'r', function ($join) {
                $join->on('c.id', '=', 'r.car_id');
            })
            ->whereNull('r.car_id');

        return $qb;
    }

    public function isExistByNumberPlate(string $numberPlate): bool
    {
        return DB::table('cars', 'c')
            ->where('c.number_plate', $numberPlate)
            ->exists();
    }
}
