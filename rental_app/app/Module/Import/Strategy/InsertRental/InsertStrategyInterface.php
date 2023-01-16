<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

interface InsertStrategyInterface
{
    public function import(array $data, string $filename, bool $isLast): void;
}
