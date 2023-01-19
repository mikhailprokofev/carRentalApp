<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Color: string
{
    use \App\Common\Enum\EnumToArray;
    case BLACK = 'black';
    case WHITE = 'white';
    case YELLOW = 'yellow';
    case BLUE = 'blue';
    case GRAY = 'gray';
}