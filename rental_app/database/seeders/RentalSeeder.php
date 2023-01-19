<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;

final class RentalSeeder extends Seeder
{
    public function run(): void
    {
        Rental::factory()
            ->count(5)
            ->create();
    }
}
