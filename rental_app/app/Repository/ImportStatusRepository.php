<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\ImportStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class ImportStatusRepository implements ImportStatusRepositoryInterface
{
    public function findByFileName(string $fileName): Collection
    {
        return ImportStatus::where('filename', $fileName)->get();
    }

    public function isExistByFileName(string $filename): bool
    {
        return DB::table('import_statuses')
            ->where(['filename' => $filename])
            ->exists();
    }
}
