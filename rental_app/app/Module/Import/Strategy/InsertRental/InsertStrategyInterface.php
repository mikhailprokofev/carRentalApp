<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Models\ImportStatus;

interface InsertStrategyInterface
{
    public function import(array $data, string $filename): ImportStatus;
}
