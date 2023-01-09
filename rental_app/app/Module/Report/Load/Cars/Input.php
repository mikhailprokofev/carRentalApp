<?php

declare(strict_types=1);

namespace App\Module\Report\Load\Cars;

final class Input
{
    public function __construct(
        private int $month,
        private int $year,
    ) {
    }

    public static function make(string $year, string $month): self
    {
        return new self(
            (int) $month,
            (int) $year,
        );
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }
}
