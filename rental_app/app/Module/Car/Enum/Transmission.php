<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Transmission
{
    case Manual;
    case Automatic;
    case Robotic;
}