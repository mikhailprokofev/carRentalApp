<?php

declare(strict_types=1);

namespace App\Module\Rate\Service;

use App\Common\Type\Price;
use App\Module\Rate\Enum\DayEnum;
use App\Module\Rate\Enum\PercentEnum;

final class RateCalculatingService implements RateCalculatingServiceInterface
{
    public function calculate(int $interval, int $baseRate): Price
    {
        $binaryInt = DayEnum::convertToBinary($interval);
        $percents = PercentEnum::convertToArray($binaryInt);
        $days = DayEnum::convertToArray($binaryInt);

        $diffDays = $this->calculateDiffDays($interval, $days);

        $payment = $this->calculatePayment($baseRate, $percents, $diffDays);

        return new Price(array_sum($payment));
    }

    private function calculateDiffDays(int $interval, array $days): array {
        $diffDays[] = 0;
        array_pop($days);
        $diffDays = array_merge($diffDays, $days);
        $diffDays[] = $interval;
        $diffDays = array_reverse($diffDays);

        for ($i = 0, $result = []; $i < count($diffDays) - 1; $i++) {
            $result[] = $diffDays[$i] - $diffDays[$i + 1];
        }

        return $result;
    }

    private function calculatePayment(int $baseRate, array $percents, array $arrDays): array
    {
        return array_map(
            fn (int $percent, int $days) => ($baseRate - $baseRate * $percent / 100) * $days,
            $percents,
            array_reverse($arrDays),
        );
    }
}
