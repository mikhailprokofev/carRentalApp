<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Country: string
{
    use EnumToArray;

    case GERMAN = 'German';
    case RUSSIA = 'Russia';
    case FRANCE = 'France';
    case KOREA = 'Korea';
    case JAPAN = 'Japan';

    public static function getByBrand($brand)
    {
        $country = (match ($brand) {
            Brand::AUDI->value         => static::GERMAN,
            Brand::VOLKSWAGEN->value   => static::GERMAN,
            Brand::RENAULT->value      => static::FRANCE,
            Brand::LADA->value         => static::RUSSIA,
            Brand::KIA->value          => static::KOREA,
            Brand::HONDA->value        => static::JAPAN,
        });
        return $country->value;
    }
}