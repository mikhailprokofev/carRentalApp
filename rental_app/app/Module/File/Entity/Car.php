<?php

declare(strict_types=1);

namespace App\Module\File\Entity;

use App\Module\File\Type\Price;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\UuidInterface;

final class Car
{
    // TODO: number_plate - создать поле (проверка значения в конструкторе на регулярку)
    public function __construct(
        private UuidInterface $id,
        private string $numberPlate,
        private Price $baseSalary,
        private string $model,
        private ?string $description,
    ) {}

    public static function make(
        string $id,
        string $numberPlate,
        string $baseSalary,
        string $model,
        ?string $description,
    ) {
        return new Car(
            UuidV7::fromString($id),
            $numberPlate,
            new Price($baseSalary),
            $model,
            $description,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(), // TODO: maybe $this->id
            'number_plate' => $this->numberPlate,
            'description' => $this->description,
            'base_salary' => $this->baseSalary->getDataBaseValue(),
            'model' => $this->model,
        ];
    }
}
