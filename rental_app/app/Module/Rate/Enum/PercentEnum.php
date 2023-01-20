<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum;

use App\Common\Enum\BinaryEnumInterface;

enum PercentEnum: int implements BinaryEnumInterface
{
    case ZERO_PERCENT = 0;
    case FIVE_PERCENT = 5;
    case TEN_PERCENT = 10;
    case FIFTEEN_PERCENT = 15;

    public function getBinaryInt(): int
    {
        return match ($this) {
            PercentEnum::FIFTEEN_PERCENT => 1 << 3,
            PercentEnum::TEN_PERCENT => 1 << 2,
            PercentEnum::FIVE_PERCENT => 1 << 1,
            PercentEnum::ZERO_PERCENT => 1 << 0,
        };
    }
}
