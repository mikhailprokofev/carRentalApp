<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Drive: string
{
    use \App\Common\Enum\EnumToArray;
    case Front = 'front';
    case Rear = 'rear';
    case Four = 'four';
    case All = 'all';
}