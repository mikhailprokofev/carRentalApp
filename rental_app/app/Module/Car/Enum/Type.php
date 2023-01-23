<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Type: string
{
    use EnumToArray;

    case Econom = 'econom';
    case Business = 'business';
    case Luxury = 'luxury';

    public function cost(): array|int
    {
        return match($this) {
            self::Econom => 1000,
            self::Business => ['from' => 1001, 'to' => 4999],
            self::Luxury => 5000,
        };
    }
}
