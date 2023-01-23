<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Car;
use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Control;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\Model;
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
        // TODO: подумать куда перенести
        $dependencies = file_get_contents(storage_path('app') . '/' . "car_dependencies.json");
        $dependencies = json_decode($dependencies, true);

        $dependence = $this->faker->randomElement($dependencies['data']);

        $brand = Brand::tryFrom($dependence['brand']);
        $model = Model::tryFrom($dependence['model']);
        $class = Type::tryFrom($dependence['class']);

        $country = $brand->country();

        $color = $this->faker->randomElement(Color::cases());
        $insurance = $this->faker->randomElement(Insurance::cases());

        $drive = is_array($dependence['drive'])
            ? $this->faker->randomElement($dependence['drive'])
            : $dependence['drive'];
        $control = is_array($dependence['control'])
            ? $this->faker->randomElement($dependence['control'])
            : $dependence['control'];
        $bodyType = is_array($dependence['body_type'])
            ? $this->faker->randomElement($dependence['body_type'])
            : $dependence['body_type'];
        $transmission = is_array($dependence['transmission'])
            ? $this->faker->randomElement($dependence['transmission'])
            : $dependence['transmission'];

        $drive = Drive::tryFrom($drive);
        $control = Control::tryFrom($control);
        $bodyType = BodyType::tryFrom($bodyType);
        $transmission = Transmission::tryFrom($transmission);

        $this->generateNumberPlate();

        $cost = $class->cost();
        $baseSalary = is_array($cost) ? $this->faker->numberBetween($cost['from'], $cost['to']) : $cost;

        return [
            'id' => $this->faker->uuid(),
            'brand' => $brand->value,
            'model' => $model->value,
            'class' => $class->value,
            'country' => $country->value,
            'color' => $color->value,
            'description' => $this->faker->realText(),
            'manufacture_date' => $this->faker->year(),
            'mileage' => $this->faker->numerify('######'),
            'drive' => $drive->value,
            'insurance' => $insurance->value,
            'control' => $control->value,
            'body_type' => $bodyType->value,
            'transmission' => $transmission->value,
            'number_plate' => $this->generateNumberPlate(),
            'base_salary' => $baseSalary * 100,
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
