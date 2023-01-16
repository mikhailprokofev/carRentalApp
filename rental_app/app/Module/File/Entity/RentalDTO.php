<?php

declare(strict_types=1);

namespace App\Module\File\Entity;

use App\Module\File\Type\Price;
use DateTimeImmutable;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\UuidInterface;

final class RentalDTO
{
    public function __construct(
        private UuidInterface $id,
        private Price $startSalary,
        private string $rentalStartAt,
        private string $rentalEndAt,
        private UuidInterface $carId,
    ) {}

    public static function make(
        string $startSalary,
        string $rentalStartAt,
        string $rentalEndAt,
        string $carId,
        ?string $id = null,
    ) {
        return new RentalDTO(
            $id ? UuidV7::fromString($id) : UuidV7::uuid7(),
            new Price($startSalary),
            $rentalStartAt,
            $rentalEndAt,
//            new DateTimeImmutable($rentalStartAt),
//            new DateTimeImmutable($rentalEndAt),
            UuidV7::fromString($carId),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'start_salary' => $this->startSalary->getDataBaseValue(),
            'car_id' => $this->carId,
//            'rental_start' => $this->rentalStartAt->format('Y-m-d'),
//            'rental_end' => $this->rentalEndAt->format('Y-m-d'),
            'rental_start' => $this->rentalStartAt,
            'rental_end' => $this->rentalEndAt,
        ];
    }
}
