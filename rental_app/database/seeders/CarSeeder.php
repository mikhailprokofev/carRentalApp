<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

final class CarSeeder extends Seeder
{
    private const COUNT_CARS = 20;

    public function run(): void
    {
//        $dependencies = file_get_contents(storage_path('app') . '/' . "car_dependencies.json");
//        $dependencies = json_decode($dependencies, true);

        Car::factory()
            ->count(self::COUNT_CARS)
            ->create();
    }
}
