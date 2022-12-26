<?php

declare(strict_types=1);

namespace App\Module\File\Entity;

use App\Module\File\Type\Price;
use DateTimeImmutable;
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
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
        private ?string $description,
    ) {}

    public static function make(
        string $numberPlate,
        string $model,
        string $baseSalary,
        ?string $description,
        ?string $id = null,
    ) {
        return new Car(
            $id ? UuidV7::fromString($id) : UuidV7::uuid7(),
            $numberPlate,
            new Price($baseSalary),
            $model,
            new DateTimeImmutable(),
            new DateTimeImmutable(),
            $description,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'number_plate' => $this->numberPlate,
            'description' => $this->description,
            'base_salary' => $this->baseSalary->getDataBaseValue(),
            'model' => $this->model,
//            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
//            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
