<?php

declare(strict_types=1);

use App\Models\Rental;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rental::factory()
            ->count(5)
                ->create();
    }
}
