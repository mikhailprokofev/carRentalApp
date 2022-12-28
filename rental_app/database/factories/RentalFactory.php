<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use App\Models\Car;

class RentalFactory extends Factory
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
            'start_salary' => 100 * $faker->numberBetween(1,15),
            'rental_start' => $faker->dateTimeInInterval('now', '+ 1 year'),
            'rental_end' => $faker->dateTimeInInterval('now', '+ 1 year'),
            'car_id'   => Car::factory(),
        ];
    }
}
