<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\UuidInterface;

final class RentalRepository implements RentalRepositoryInterface
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

    public function findLastRentalByCar(UuidInterface $catId): object
    {
        return DB::table('rentals')
            ->where('car_id', $catId)
            ->latest('rental_end')
            ->first();
    }

    public function findLastRentals(): Collection
    {
        return DB::table('rentals')
            ->latest('rental_end')
            ->groupBy('car_id')
            ->get();
    }

    public function find(UuidInterface $catId, string $start, string $end)
    {
        return DB::table('rentals')
//            ->where('car_id', $catId)
            ->whereRaw("[$start, $end] && [rental_start, rental_end]")
            ->get();
//            ->latest('rental_end')
//            ->first();
    }
}
