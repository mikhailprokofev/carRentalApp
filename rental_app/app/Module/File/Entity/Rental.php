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
    ) {}

    public static function make(
        string $id,
        string $startSalary,
        string $rentalStartAt,
        string $rentalEndAt,
    ) {
        return new Rental(
            UuidV7::fromString($id),
            new Price($startSalary),
            new DateTimeImmutable($rentalStartAt),
            new DateTimeImmutable($rentalEndAt),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(), // TODO: maybe $this->id
            'base_salary' => $this->startSalary->getDataBaseValue(),
            'rental_start_at' => $this->rentalStartAt->format('Y-m-d H:i:s'),
            'rental-end-at' => $this->rentalEndAt->format('Y-m-d H:i:s'),
        ];
    }
}
