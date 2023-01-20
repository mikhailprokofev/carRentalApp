<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Type: string
{
    use EnumToArray;

    case Econom = 'econom';
    case Business = 'business';
    case Luxury = 'luxury';
}
