<?php

declare(strict_types=1);

namespace App\Module\Rate\Repository;

use Illuminate\Support\Facades\DB;

final class RentalRepository implements RentalRepositoryInterface
{
    public function insert(array $data): void
    {
        DB::table('rentals')->insert($data);
    }

    public function update(array $data): void
    {
        DB::table('rentals')->upsert($data, ['cars_id', 'rental_start', 'rental_end'], ['start_salary']);
    }

    public function truncate(): void
    {
        DB::table('rentals')->truncate();
    }
}
