<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CustomRentalRepository implements CustomRentalRepositoryInterface
{
    public function insert(array $data): void
    {
        DB::table('rentals')->insert($data);
        Log::info('4');
    }

    public function update(array $data): void
    {
        DB::table('rentals')->upsert($data, ['car_id', 'rental_start', 'rental_end'], ['start_salary']);
    }

    public function truncate(): void
    {
        DB::table('rentals')->truncate();
    }

    private function subQueryFormatInterval(int $year, int $month): Builder
    {
        $qb = DB::table('rentals', 'r')
            ->select(
                'r.car_id',
                DB::raw("CASE
                    WHEN EXTRACT(month from r.rental_start) != 12
                        THEN date_trunc('month', r.rental_end)
                    ELSE r.rental_start
                END as rental_start"),
                DB::raw("CASE
                    WHEN EXTRACT(month from r.rental_end) != 12
                        THEN date_trunc('month', r.rental_start) + '1 month'::INTERVAL - '1 day'::INTERVAL
                    ELSE r.rental_end
                END as rental_end"),
            )
            ->where(function ($query) use ($month) {
                $query->whereRaw("EXTRACT(month from r.rental_start) = $month")
                    ->orWhereRaw("EXTRACT(month from r.rental_end) = $month");
            })
            ->whereRaw("EXTRACT(year from rental_end) = $year");

        return $qb;
    }

    public function findLoadCarsInfo(int $year, int $month): Collection
    {
        $subQb = $this->subQueryFormatInterval($year, $month);

        $qb = DB::table('cars', 'c')
            ->select(
                'c.number_plate',
                DB::raw('(sum(EXTRACT(day from r.rental_end) - EXTRACT(day from r.rental_start))) as diff'),
            )->leftJoinSub($subQb, 'r', function ($join) {
                $join->on('r.car_id', '=', 'c.id');
            })
            ->groupBy(DB::raw('rollup (c.number_plate)'));

        return $qb->get();
    }
}
