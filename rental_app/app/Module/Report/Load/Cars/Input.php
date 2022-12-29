<?php

declare(strict_types=1);

namespace App\Module\Report\Load\Cars;

use Illuminate\Http\Request;

final class Input
{
    public function __construct(
        private int $month,
        private int $year,
    ) {}

    public static function make(Request $request): self
    {
        return new self(
            (int) $request->get('month'),
            (int) $request->get('year'),
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
