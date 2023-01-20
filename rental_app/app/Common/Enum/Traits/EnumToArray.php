<?php

declare(strict_types=1);

namespace App\Common\Enum\Traits;

trait EnumToArray
{
    public static function toArray(): array
    {
        return array_map(fn(self $enum) => $enum->value, self::cases());
    }
}
