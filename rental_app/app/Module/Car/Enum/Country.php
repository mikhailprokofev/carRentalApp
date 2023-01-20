<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\BinaryEnumInterface;
use App\Common\Enum\Traits\EnumToArray;

enum Country: string implements BinaryEnumInterface
{
    public const BINARY_INT = 1;

    use EnumToArray;

    case GERMAN = 'German';
    case RUSSIA = 'Russia';
    case FRANCE = 'France';
    case KOREA = 'Korea';
    case JAPAN = 'Japan';

    public function getBinaryInt(): int
    {
        return match ($this) {
            self::GERMAN => self::BINARY_INT << 0,
            self::RUSSIA => self::BINARY_INT << 1,
            self::FRANCE => self::BINARY_INT << 2,
            self::KOREA => self::BINARY_INT << 3,
            self::JAPAN => self::BINARY_INT << 4,
        };
    }
}