<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Control: string
{
    use EnumToArray;

    case RIGHT = 'right';
    case LEFT = 'left';

    public static function isRight($side): bool
    {
        return $side == self::RIGHT->name;
    }
}
