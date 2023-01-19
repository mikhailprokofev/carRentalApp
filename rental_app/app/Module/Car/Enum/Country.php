<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\EnumToArray;

enum Country: string
{
    use EnumToArray;

    case GERMAN = 'German';
    case RUSSIA = 'Russia';
    case FRANCE = 'France';
    case KOREA = 'Korea';
    case JAPAN = 'Japan';

    public function getByBrand($brand)
    {
        return match ($brand) {
            Brand::AUDI         => static::GERMAN,
            Brand::VOLKSWAGEN   => static::GERMAN,
            Brand::RENAULT      => static::FRANCE,
            Brand::LADA         => static::RUSSIA,
            Brand::KIA          => static::KOREA,
            Brand::HONDA        => static::JAPAN,
        };
    }
}