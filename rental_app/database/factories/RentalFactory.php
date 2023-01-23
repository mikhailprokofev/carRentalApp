<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Car;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

final class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = Faker::create();

        $car = (Car::factory()->count(1)->create())[0];

        $interval = $faker->numberBetween(0, 30);
        $start = $faker->dateTimeInInterval('now', '+1 day');
        $end = $faker->dateTimeInInterval($start, "+$interval days");

        return [
            'start_salary' => $car['base_salary'],
            'rental_start' => $start,
            'rental_end' => $end,
            'car_id' => $car['id'],
        ];
    }
}
