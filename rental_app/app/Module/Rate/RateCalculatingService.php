<?php

declare(strict_types=1);

namespace App\Module\Rate;

use App\Common\Type\Price;
use App\Module\Rate\Enum\RateCalculatingEnum;

final class RateCalculatingService
{
    public function calculate(int $interval, int $baseRate): Price
    {
        $percents = $this->calculatePercent($interval);
        $daysArr = $this->calculateDays($interval);

        $payment = array_map(
            fn (int $percent, int $days) => ($baseRate - $baseRate * $percent / 100) * $days,
            $percents,
            $daysArr,
        );

        return new Price(array_sum($payment));
    }

    public function calculateDays(int $interval): array {
        $match = match(true) {
            $interval > RateCalculatingEnum::SEVENTEEN_DAY->value => RateCalculatingEnum::SEVENTEEN_DAY->value,
            $interval > RateCalculatingEnum::NINE_DAY->value => RateCalculatingEnum::NINE_DAY->value,
            $interval > RateCalculatingEnum::FOUR_DAY->value => RateCalculatingEnum::FOUR_DAY->value,
        };

        foreach (array_reverse(RateCalculatingEnum::cases()) as $case) {
            if ($case->value > $match) {
                continue;
            }

            $result[] = $interval - $case->value;
            $interval = $case->value;
        }
        $result[] = $interval;

        return $result;
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
