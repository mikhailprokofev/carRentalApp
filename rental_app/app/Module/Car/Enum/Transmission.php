<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Transmission: string
{
    use \App\Common\Enum\EnumToArray;
    case Manual = 'manual';
    case Automatic = 'automatic';
    case Robotic ='robotic';
}