<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Drive: string
{
    use EnumToArray;

    case Front = 'front';
    case Rear = 'rear';
    case Four = 'four';
    case All = 'all';
}
