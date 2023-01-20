<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum BodyType: string
{
    use EnumToArray;

    case SEDAN = 'sedan';
    case HETCHBACK = 'hetchback';
    case SPORT = 'sport';
    case SUV = 'suv';
    case UNIVERSAL = 'universal';
}
