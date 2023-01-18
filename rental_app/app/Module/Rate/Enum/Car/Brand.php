<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum\Car;

enum Brand
{
    case AUDI;
    case VOLKSWAGEN;
    case HONDA;
    case LADA;
    case RENAULT;
    case KIA;

    public function getByCountry($country)
    {
        return match ($country) {
            Country::GERMAN => [
                static::AUDI,
                static::VOLKSWAGEN
            ],
            Country::FRANCE => [static::RENAULT],
            Country::RUSSIA => [static::LADA],
            Country::KOREA  => [static::KIA],
            Country::JAPAN  => [static::HONDA],
        };
    }
}