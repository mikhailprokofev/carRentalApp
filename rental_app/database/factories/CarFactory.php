<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Car;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Drive;
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
//        $faker = \Faker\Factory::create();

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

        $brand = $this->faker->randomElement(Brand::cases());
        $country = $brand->country();
        $color = $this->faker->randomElement(Color::cases());
        $drive = $this->faker->randomElement(Drive::cases());

        return [
            'id' => $this->faker->uuid(),
            'brand' => $brand->value,
            'country' => $country->value,
            'color' => $color->value,
            'description' => $this->faker->realText(),
            'manufacture_date' => $this->faker->date(),
            'mileage' => $this->faker->numerify('######'),
            'drive' => $drive->value,

            'number_plate' => $this->faker->languageCode() .
                $this->faker->numberBetween(100, 999) .
                $this->faker->languageCode(),
            'type' => $typesArray[
                $this->faker->numberBetween(0, 2)
            ],
            'base_salary' => 100 * $this->faker->numberBetween(1, 15),
            'model' => $carModels[
                $this->faker->numberBetween(0, 2)
            ],
        ];
    }
}
