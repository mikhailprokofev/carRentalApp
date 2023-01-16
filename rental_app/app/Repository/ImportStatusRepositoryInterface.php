<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;

interface ImportStatusRepositoryInterface
{
    public function findByFileName(string $fileName): Collection;
}
