<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\BinaryEnumInterface;
use App\Common\Enum\Traits\EnumToArray;

enum Brand: string implements BinaryEnumInterface
{
    use EnumToArray;
    
    public const BINARY_INT = 1;

    case AUDI = 'Audi';
    case VOLKSWAGEN = 'Volkswagen';
    case HONDA = 'Honda';
    case LADA = 'Lada';
    case RENAULT = 'Renault';
    case KIA = 'Kia';

    public function country(): Country
    {
        return match ($this) {
            self::AUDI, self::VOLKSWAGEN => Country::GERMAN,
            self::RENAULT => Country::FRANCE,
            self::LADA => Country::RUSSIA,
            self::KIA => Country::KOREA,
            self::HONDA => Country::JAPAN,
        };
    }

    public function getBinaryInt(): int
    {
        return match ($this) {
            self::AUDI => self::BINARY_INT << 0,
            self::VOLKSWAGEN => self::BINARY_INT << 1,
            self::LADA => self::BINARY_INT << 2,
            self::RENAULT => self::BINARY_INT << 3,
            self::KIA => self::BINARY_INT << 4,
            self::HONDA => self::BINARY_INT << 5,
        };
    }
}
