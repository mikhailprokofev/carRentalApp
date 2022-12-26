<?php

declare(strict_types=1);

namespace App\Module\File\Type;

final class Price
{
    private string $value;

    public function __construct(
        string $value,
    ) {
        $this->value = bcdiv($value, '1', 2);
    }

    public function getDataBaseValue(): float
    {
        return $this->value * 100;
    }

    public function getApiValue(): float
    {
        return (float) $this->value;
    }
}
