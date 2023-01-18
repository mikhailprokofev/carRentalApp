<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Country
{
    case GERMAN;
    case RUSSIA;
    case FRANCE;
    case KOREA;
    case JAPAN;

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