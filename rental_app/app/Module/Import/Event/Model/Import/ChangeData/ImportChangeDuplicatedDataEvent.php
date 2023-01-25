<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Model\Import\ChangeData;

use App\Models\ImportStatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ImportChangeDuplicatedDataEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        private ImportStatus $importStatus,
        private int $countRows,
    ) {}

    public function getImportStatus(): ImportStatus
    {
        return $this->importStatus;
    }

    public function getCountRows(): int
    {
        return $this->countRows;
    }

    public function getTypeRows(): string
    {
        return 'duplicated_rows';
    }
}
