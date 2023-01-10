<?php

declare(strict_types=1);

namespace App\Common\Type;

final class Price
{
    private int $value;

    public function __construct(
        int $value,
    ) {
        $this->value = (int) bcdiv((string) $value, '1', 2);
    }

    public function getDataBaseValue(): int
    {
        return $this->value;
    }

    public function getApiValue(): float
    {
        return (float) $this->value / 100;
    }
}
