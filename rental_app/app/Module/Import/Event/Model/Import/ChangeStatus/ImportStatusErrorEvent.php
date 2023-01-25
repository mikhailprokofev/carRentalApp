<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Model\Import\ChangeStatus;

use App\Models\ImportStatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ImportStatusErrorEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        private ?ImportStatus $importStatus,
        private string $filename,
    ) {}

    public function getImportStatus(): ?ImportStatus
    {
        return $this->importStatus;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
