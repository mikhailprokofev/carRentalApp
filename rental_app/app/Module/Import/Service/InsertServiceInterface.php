<?php

declare(strict_types=1);

namespace App\Module\Import\Service;

interface InsertServiceInterface
{
    public function commitData(array $data): void;
}
