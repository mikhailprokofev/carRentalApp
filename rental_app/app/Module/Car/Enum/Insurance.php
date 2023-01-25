<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Insurance: string
{
    use EnumToArray;

    case OSAGO = 'OSAGO';
    case KASKO = 'KASKO';
    case TEST = 'tst';
}
