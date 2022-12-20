<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum;

enum PercentEnum: int
{
    case ZERO_PERCENT = 0;
    case FIVE_PERCENT = 5;
    case TEN_PERCENT = 10;
    case FIFTEEN_PERCENT = 15;

    private function getBinaryInt(): int
    {
        return match($this) {
            PercentEnum::FIFTEEN_PERCENT => 1 << 3,
            PercentEnum::TEN_PERCENT => 1 << 2,
            PercentEnum::FIVE_PERCENT => 1 << 1,
            PercentEnum::ZERO_PERCENT => 1 << 0,
        };
    }

    public static function convertToArray(int $binaryInt): array {
        $result = [];
        foreach (PercentEnum::cases() as $case) {
            if ($binaryInt & $case->getBinaryInt()) {
                $result[] = $case->value;
            }
        }
        return $result;
//        return match(true) {
//            $days == 1 >> 3 => [PercentEnum::ZERO_PERCENT, PercentEnum::FIVE_PERCENT, PercentEnum::TEN_PERCENT, PercentEnum::FIFTEEN_PERCENT],
//            $days == 1 >> 2 => [PercentEnum::ZERO_PERCENT, PercentEnum::FIVE_PERCENT, PercentEnum::TEN_PERCENT],
//            $days == 1 >> 1 => [PercentEnum::ZERO_PERCENT, PercentEnum::FIVE_PERCENT],
//            $days == 1 >> 0 => [PercentEnum::ZERO_PERCENT],
//            default => [],
//        };
    }
}
