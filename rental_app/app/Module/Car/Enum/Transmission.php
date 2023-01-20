<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Transmission: string
{
    use EnumToArray;

    case Manual = 'manual';
    case Automatic = 'automatic';
    case Robotic ='robotic';
}
