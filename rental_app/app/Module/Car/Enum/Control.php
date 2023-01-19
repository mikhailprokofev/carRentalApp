<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Control : string
{

    use \App\Common\Enum\EnumToArray;
    case RIGHT = 'right';
    case LEFT = 'left';

    public static function isRight($side)
    {
        return $side == static::RIGHT->name;
    }
}