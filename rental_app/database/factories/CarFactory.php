<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Car;
use App\Module\Car\Dependency\EnumDependencies;
use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Control;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\Transmission;
use App\Module\Car\Enum\Type;
use App\Module\Car\Utility\NumberPlate\NumberPlate;
use App\Module\Car\Utility\NumberPlate\NumberPlateDict;
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
        $brand = $this->faker->randomElement(Brand::cases());
        $country = $brand->country();
        $color = $this->faker->randomElement(Color::cases());
        $drive = $this->faker->randomElement(Drive::cases());
        $insurance = $this->faker->randomElement(Insurance::cases());
        $control = $this->faker->randomElement(Control::cases());
        $bodyType = $this->faker->randomElement(BodyType::cases());
        $transmission = $this->faker->randomElement(Transmission::cases());
        $model = $this->faker->randomElement(EnumDependencies::getModelsByBrand($brand));

        $this->generateNumberPlate();

        return [
            'id' => $this->faker->uuid(),
            'brand' => $brand->value,
            'country' => $country->value,
            'color' => $color->value,
            'description' => $this->faker->realText(),
            'manufacture_date' => $this->faker->year(),
            'mileage' => $this->faker->numerify('######'),
            'drive' => $drive->value,
            'model' => $model->value,
            'insurance' => $insurance->value,
            'control' => $control->value,
            'body_type' => $bodyType->value,
            'transmission' => $transmission->value,
            'number_plate' => $this->generateNumberPlate(),

            // class + base_salary

            'class' => Type::Econom->value,
            'base_salary' => 100 * $this->faker->numberBetween(1, 15),
        ];
    }

    private function generateNumberPlate(): string
    {
        $numberPlate = $this->generateNumberPlateByTemplate();

        $numberPlateUtility = new NumberPlate();
        while (!$numberPlateUtility->isCorrect($numberPlate)) {
            $numberPlate = $this->generateNumberPlateByTemplate();
        }

        return $numberPlate;
    }

    private function generateNumberPlateByTemplate(): string
    {
        $numberPlate[] = $this->faker->randomElement(NumberPlateDict::$availableChars);
        $numberPlate[] = $this->faker->numerify();
        $numberPlate[] = implode('', $this->faker->randomElements(NumberPlateDict::$availableChars, 2));

        $countNumbRegion = $this->faker->numberBetween(2, 3);
        $numberPlate[] = $this->faker->numerify(str_pad('', $countNumbRegion, '#'));

        return implode('', $numberPlate);
    }
}
