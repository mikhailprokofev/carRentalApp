<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\ListAvailable;

use DateTimeImmutable;
use Illuminate\Http\Request;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Ramsey\Uuid\UuidInterface;

final class Input
{
    public function __construct(
        private DateTimeImmutable $startAt,
        private DateTimeImmutable $endAt,
        private ?int $min_salary,
        private ?int $max_salary,
    ) {}

    public static function make(Request $request): self
    {
        return new self(
            new DateTimeImmutable($request->get('start_at')),
            new DateTimeImmutable($request->get('end_at')),
            (int)$request->get('min_salary') * 100,
            (int)$request->get('max_salary') * 100
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

    public function getMinSalary(): ?int
    {
        return $this->min_salary;
    }

    public function getMaxSalary(): ?int
    {
        return $this->max_salary;
    }
}
