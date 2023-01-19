<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Insurance: string
{
    use \App\Common\Enum\EnumToArray;
    case OSAGO = 'OSAGO';
    case KASKO = 'KASKO';
}