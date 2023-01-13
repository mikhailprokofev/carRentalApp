<?php

declare(strict_types=1);

namespace App\Common\Utility;

use DateTimeImmutable;

final class CalculatorDate
{
    public static function calculateMonthLastDay(int $year, int $month, string $format = 'Y-m-t'): int
    {
        $monthFirstDay = (new DateTimeImmutable("$year-$month-01"))->format($format);

        return (int) date('d', strtotime($monthFirstDay));
    }
}
