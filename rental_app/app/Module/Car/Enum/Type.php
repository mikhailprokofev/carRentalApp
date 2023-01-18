<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\EnumValidatorInterface;

enum Type implements EnumValidatorInterface
{
    case Econom;
    case Busines;
    case Luxury;
}