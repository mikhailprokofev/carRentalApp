<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

final class CarFactory extends Factory
{
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        $typesArray = [
            'Econom',
            'Busines',
            'Premium',
        ];

        $carModels = [
            'Lada Vesta',
            'Honda Civic',
            'Bugatty',
            'Lada Granta',
            'Reno Logan',
        ];

        return [
            'id' => $faker->uuid(),
            'number_plate' => $faker->languageCode() .
                $faker->numberBetween(100, 999) .
                $faker->languageCode(),
            'color' => $faker->colorName(),
            'type' => $typesArray[
                $faker->numberBetween(0, 2)
            ],
            'description' => $faker->text(80),
            'base_salary' => 100 * $faker->numberBetween(1, 15),
            'model' => $carModels[
                $faker->numberBetween(0, 2)
            ],
        ];
    }
}
