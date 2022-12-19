<?php

declare(strict_types=1);

namespace App\Common\Type;

final class Price
{
    public function __construct(
        private int $value,
    ) {}

    public function getValue(): float
    {
        return $this->value / 100;
    }


}
