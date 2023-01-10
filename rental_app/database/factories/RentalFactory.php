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

        return [
            'start_salary' => 1000 * $faker->numberBetween(1, 5),
            'rental_start' => $faker->dateTimeInInterval('now', '+ 1 year'),
            'rental_end' => $faker->dateTimeInInterval('now', '+ 1 year'),
            'car_id' => Car::factory(),
        ];
    }
}
