<?php

declare(strict_types=1);

namespace App\Common\Utility;

final class CalculatorPercent
{
    public static function calculate(int|float $full, int|float $part, $precision = 2, $default = 0): float
    {
        return round($part && $full ? $part / $full * 100 : $default, $precision);
    }
}
