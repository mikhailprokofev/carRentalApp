<?php

declare(strict_types=1);

namespace App\Module\Rate;

use App\Common\Type\Price;
use App\Module\Rate\Service\RateCalculatingEnum;

final class RateCalculatingService
{
    public function calculate(int $days, int $basePay): Price
    {
        $percents = $this->calculatePercent($days);
        $days = $this->calculateDays($days);

        $payment = array_map(fn (int $percent, int $interval) => ($basePay - $basePay * $percent / 100) * $interval, $percents, array_reverse($days));
        return new Price(array_sum($payment));
    }

    public function calculateDays(int $interval): array {
//        RateCalculatingEnum::FOUR_DAY->value,
//                RateCalculatingEnum::NINE_DAY->value - RateCalculatingEnum::FOUR_DAY->value,
//                RateCalculatingEnum::SEVENTEEN_DAY->value - RateCalculatingEnum::NINE_DAY->value,
//                $interval - RateCalculatingEnum::SEVENTEEN_DAY->value
        $match = match(true) {
            $interval > RateCalculatingEnum::SEVENTEEN_DAY->value => RateCalculatingEnum::SEVENTEEN_DAY->value,
            $interval > RateCalculatingEnum::NINE_DAY->value => RateCalculatingEnum::SEVENTEEN_DAY->value,
            $interval > RateCalculatingEnum::FOUR_DAY->value => RateCalculatingEnum::SEVENTEEN_DAY->value,
        };

        return [];
    }

    public function calculatePercent(int $interval): array {
        return match(true) {
            $interval > RateCalculatingEnum::SEVENTEEN_DAY->value => [15, 10, 5, 0],
            $interval > RateCalculatingEnum::NINE_DAY->value => [10, 5, 0],
            $interval > RateCalculatingEnum::FOUR_DAY->value => [5, 0],
            default => [0],
        };
    }
}
