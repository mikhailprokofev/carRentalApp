<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\ImportStatus;
use Illuminate\Support\Collection;

final class ImportStatusRepository implements ImportStatusRepositoryInterface
{
    public function findByFileName(string $fileName): Collection
    {
        return ImportStatus::where('filename', $fileName)->get();
    }
}
