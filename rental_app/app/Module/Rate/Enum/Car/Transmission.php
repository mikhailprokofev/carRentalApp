<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum\Car;

enum Transmission
{
    case Manual;
    case Automatic;
    case Robotic;
}