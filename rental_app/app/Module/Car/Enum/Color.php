<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Color: string
{
    case BLACK = 'black';
    case WHITE = 'white';
    case YELLOW = 'yellow';
    case BLUE = 'blue';
    case GRAY = 'gray';
}