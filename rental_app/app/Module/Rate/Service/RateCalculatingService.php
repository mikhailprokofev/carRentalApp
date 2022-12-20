<?php

declare(strict_types=1);

namespace App\Module\Rate\Service;

use App\Common\Type\Price;
use App\Module\Rate\Enum\RateCalculatingEnum;

final class RateCalculatingService implements RateCalculatingServiceInterface
{
    public function calculate(int $interval, int $baseRate): Price
    {
        $percents = $this->calculatePercent($interval);
        $days = $this->calculateDays($interval);
        $payment = $this->calculatePayment($baseRate, $percents, $days);

        return new Price(array_sum($payment));
    }

    private function calculateDays(int $interval): array {
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

    private function calculatePercent(int $interval): array {
        return match(true) {
            $interval > RateCalculatingEnum::SEVENTEEN_DAY->value => [15, 10, 5, 0],
            $interval > RateCalculatingEnum::NINE_DAY->value => [10, 5, 0],
            $interval > RateCalculatingEnum::FOUR_DAY->value => [5, 0],
            default => [0],
        };
    }

    private function calculatePayment(int $baseRate, array $percents, array $arrDays): array
    {
        return array_map(
            fn (int $percent, int $days) => ($baseRate - $baseRate * $percent / 100) * $days,
            $percents,
            $arrDays,
        );
    }
}
