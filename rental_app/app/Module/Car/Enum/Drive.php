<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Drive: string
{
    case FRONT = 'front';
    case REAR = 'rear';
}