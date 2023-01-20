<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum;

use App\Common\Enum\BinaryEnumInterface;

enum DayEnum: int implements BinaryEnumInterface
{
    case FOUR_DAY = 4;
    case NINE_DAY = 9;
    case SEVENTEEN_DAY = 17;
    case THIRTY_DAY = 30;

    public function getBinaryInt(): int
    {
        return match ($this) {
            DayEnum::THIRTY_DAY => 1 << 3,
            DayEnum::SEVENTEEN_DAY => 1 << 2,
            DayEnum::NINE_DAY => 1 << 1,
            DayEnum::FOUR_DAY => 1 << 0,
        };
    }
}
