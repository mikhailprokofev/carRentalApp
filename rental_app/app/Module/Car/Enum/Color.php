<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Color: string
{
    use EnumToArray;

//    case BLACK = 'black';
//    case WHITE = 'white';
//    case YELLOW = 'yellow';
//    case BLUE = 'blue';
//    case GRAY = 'gray';
    case ORANGE = 'orange';
    case RED = 'red';
}
