<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum BodyType: string
{
    case SEDAN = 'sedan';
    case HETCHBACK = 'hetchback';
    case SPORT = 'sport';
    case SUV = 'suv';
    case UNIVERSAL = 'universal';
}