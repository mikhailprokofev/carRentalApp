<?php

declare(strict_types=1);

use Database\Seeders\CarSeeder;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CarSeeder::class,
        ]);
    }
}
