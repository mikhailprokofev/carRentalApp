<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Insurance: string
{
    case OSAGO = 'OSAGO';
    case KASKO = 'KASKO';
}