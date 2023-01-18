<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Type: string
{
    case Econom = 'econom';
    case Business = 'business';
    case Luxury = 'luxury';
}