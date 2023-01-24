<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Listener\Import;

use App\Models\ImportStatus;
use App\Module\Import\Event\Model\Import\ImportInitEvent;

final class ImportInitListener
{
    public function handle(ImportInitEvent $event): void
    {
        ImportStatus::initImport($event->getFilename());
    }
}
