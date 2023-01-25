<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Model\Import\ChangeStatus;

use App\Models\ImportStatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ImportStatusProgressEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        private ImportStatus $importStatus,
    ) {}

    public function getImportStatus(): ImportStatus
    {
        return $this->importStatus;
    }
}
