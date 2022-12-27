<?php

declare(strict_types=1);

namespace App\Module\File\Entity;

use App\Module\File\Type\Price;
use DateTimeImmutable;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\UuidInterface;

final class Rental
{
    public function __construct(
        private UuidInterface $id,
        private Price $startSalary,
        private DateTimeImmutable $rentalStartAt,
        private DateTimeImmutable $rentalEndAt,
        private UuidInterface $carId,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function make(
        string $startSalary,
        string $rentalStartAt,
        string $rentalEndAt,
        string $carId,
        ?string $id = null,
    ) {
        return new Rental(
            $id ? UuidV7::fromString($id) : UuidV7::uuid7(),
            new Price($startSalary),
            new DateTimeImmutable($rentalStartAt),
            new DateTimeImmutable($rentalEndAt),
            UuidV7::fromString($carId),
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'start_salary' => $this->startSalary->getDataBaseValue(),
            'car_id' => $this->carId,
            'rental_start' => $this->rentalStartAt->format('Y-m-d'),
            'rental_end' => $this->rentalEndAt->format('Y-m-d'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
