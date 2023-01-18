<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Brand: string
{
    case AUDI = 'Audi';
    case VOLKSWAGEN = 'Volkswagen';
    case HONDA = 'Honda';
    case LADA = 'Lada';
    case RENAULT = 'Renault';
    case KIA = 'Kia';

    public static function getByCountry($country): array
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