<?php

declare(strict_types=1);

namespace App\Module\Rate\Service;

use App\Common\Enum\Utility\EnumBinaryConvert;
use App\Module\Rate\Enum\DayEnum;
use App\Module\Rate\Enum\PercentEnum;

final class RateCalculatingService implements RateCalculatingServiceInterface
{
    private int $rate;
    private array $percents;
    private array $diffDays;
    public function __construct(int $interval, int $rate)
    {
        $this->rate = $rate;
        $binaryInt = EnumBinaryConvert::convertToBinary(DayEnum::class, $interval);
        $days = EnumBinaryConvert::convertToArray(DayEnum::class, $binaryInt);
        $this->percents = EnumBinaryConvert::convertToArray(PercentEnum::class, $binaryInt);
        $this->diffDays = $this->calculateDiffDays($interval, $days);
    }
    public function calculate(): int
    {
        $payment = $this->calculatePayment($this->percents, $this->diffDays);
        return (int)($this->rate * array_sum($payment));
    }

    public function reCalculate(): int
    {
        $payment = $this->reCalculatePayment($this->percents, $this->diffDays);
        return (int) ($this->rate / array_sum($payment));
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

    private function calculatePayment(array $percents, array $days): array
    {
        return array_map(
            fn (int $percent, int $interval) => (((100 - $percent) / 100) * $interval),
            $percents,
            array_reverse($days),
        );
    }

    private function reCalculatePayment(array $percents, array $days): array
    {
        return array_map(
            fn (int $percent, int $interval) => (((100 - $percent) / 100) * $interval),
            $percents,
            array_reverse($days),
        );
    }
}
