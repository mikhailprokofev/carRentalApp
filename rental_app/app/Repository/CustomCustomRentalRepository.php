<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CustomCustomRentalRepository implements CustomRentalRepositoryInterface
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
}
