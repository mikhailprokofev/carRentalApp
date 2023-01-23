<?php

declare(strict_types=1);

namespace App\Module\Rate\Service;

use App\Common\Enum\Utility\EnumBinaryConvert;
use App\Module\Rate\Enum\DayEnum;
use App\Module\Rate\Enum\PercentEnum;


final class RateCalculatingService implements RateCalculatingServiceInterface
{
    public function calculate(int $interval, int|null $baseRate, string $strategy = 'into'): int|null
    {
        if (empty($baseRate))
            return null;

        $binaryInt = EnumBinaryConvert::convertToBinary(DayEnum::class, $interval);
        $percents = EnumBinaryConvert::convertToArray(PercentEnum::class, $binaryInt);
        $days = EnumBinaryConvert::convertToArray(DayEnum::class, $binaryInt);

        $diffDays = $this->calculateDiffDays($interval, $days);

        $payment = $this->calculateItems($percents, $diffDays);
        return (int) $this->calculateMethod($baseRate,$payment,$strategy);
    }

    private function calculateDiffDays(int $interval, array $days): array
    {
        $arr[] = 0;
        $arr = array_merge($arr, $days);
        $arr[$lastIndex = count($arr) - 1] = $interval;

        for ($i = $lastIndex, $diffDays = []; $i > 0; $i--) {
            $diffDays[] = $arr[$i] - $arr[$i - 1];
        }

        return $diffDays;
    }

    private function calculateMethod(int $baseRate, array $payment, string $strategy): float
    {
        return match ($strategy) {
            'into' => $baseRate * array_sum($payment),
            'reverse' => $baseRate / array_sum($payment),
        };
    }

    private function calculateItems(array $percents, array $days): array
    {
        return array_map(
            fn (int $percent, int $interval) => (((100 - $percent) / 100) * $interval),
            $percents,
            array_reverse($days),
        );
    }
}
