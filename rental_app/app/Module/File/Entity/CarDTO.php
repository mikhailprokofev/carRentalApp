<?php

declare(strict_types=1);

namespace App\Module\File\Entity;

use App\Module\File\Type\Price;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\UuidInterface;

final class CarDTO
{
    public function __construct(
        private string $numberPlate,
        private string $class,
        private string $model,
        private string $brand,
        private Price $baseSalary,
        private string $color,
        private string $drive,
        private string $control,
        private string $bodyType,
        private string $transmission,
        private string $insurance,
        private int $manufactureDate,
        private int $mileage,
        private UuidInterface $id,
        private ?string $description,
    ) {}

    public static function make(
        string $numberPlate,
        string $class,
        string $model,
        string $brand,
        string $baseSalary,
        string $color,
        string $drive,
        string $control,
        string $bodyType,
        string $transmission,
        string $insurance,
        string $manufactureDate,
        string $mileage,
        ?string $description,
        ?string $id = null,
    ) {
        return new CarDTO(
            $numberPlate,
            $class,
            $model,
            $brand,
            new Price($baseSalary),
            $color,
            $drive,
            $control,
            $bodyType,
            $transmission,
            $insurance,
            (int) $manufactureDate,
            (int) $mileage,
            $id ? UuidV7::fromString($id) : UuidV7::uuid7(),
            $description,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'number_plate' => $this->numberPlate,
            'class' => $this->class,
            'model' => $this->model,
            'brand' => $this->brand,
            'base_salary' => $this->baseSalary->getDataBaseValue(),
            'color' => $this->color,
            'drive' => $this->drive,
            'control' => $this->control,
            'body_type' => $this->bodyType,
            'transmission' => $this->transmission,
            'insurance' => $this->insurance,
            'manufacture_date' => $this->manufactureDate,
            'mileage' => $this->mileage,
            'description' => $this->description,
        ];
    }
}
