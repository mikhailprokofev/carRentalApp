<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Model\Import;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ImportInitEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        private string $filename,
    ) {}

    public function getFilename(): string
    {
        return $this->filename;
    }
}
