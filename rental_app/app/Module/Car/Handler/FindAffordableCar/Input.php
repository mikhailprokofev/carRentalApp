<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\FindAffordableCar;

use DateTimeImmutable;
use Illuminate\Http\Request;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\UuidInterface;

final class Input
{
    public function __construct(
        private DateTimeImmutable $startAt,
        private DateTimeImmutable $endAt,
        private ?UuidInterface $carId,
    ) {}

    public static function make(Request $request): static
    {
        return new self(
            new DateTimeImmutable($request->get('start_at')),
            new DateTimeImmutable($request->get('end_at')),
            $request->get('car_id') ? UuidV7::fromString($request->get('car_id')) : null,
        );
    }

    public function getStartAt(): string
    {
        return $this->startAt->format('Y-m-d');
    }

    public function getEndAt(): string
    {
        return $this->endAt->format('Y-m-d');
    }

    public function getCarId(): ?UuidInterface
    {
        return $this->carId;
    }
}
