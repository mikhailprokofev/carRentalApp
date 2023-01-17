<?php

declare(strict_types=1);

namespace App\Module\Import\Service;

use App\Models\ImportStatus;
use Closure;

interface InsertServiceInterface
{
    public function recursionInsert(array $data, Closure $commitData, ImportStatus $importStatus): void;
}
