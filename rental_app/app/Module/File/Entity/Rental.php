<?php

declare(strict_types=1);

namespace App\Module\File\Entity;

use App\Module\File\Type\Price;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final class Rental
{
    public function __construct(
        private UuidInterface $id,
        private Price $startSalary,
        private DateTimeImmutable $rentalStartAt,
        private DateTimeImmutable $rentalEndAt,
    ) {}

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStartSalary(): Price
    {
        return $this->startSalary;
    }

    public function getRentalStartAt(): DateTimeImmutable
    {
        return $this->rentalStartAt;
    }

    public function getRentalEndAt(): DateTimeImmutable
    {
        return $this->rentalEndAt;
    }

}
