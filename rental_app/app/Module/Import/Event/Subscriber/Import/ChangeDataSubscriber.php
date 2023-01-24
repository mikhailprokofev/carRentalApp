<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Subscriber\Import;

use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeDuplicatedDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeInsertedDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeReadDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeValidatedDataEvent;
use Illuminate\Events\Dispatcher;

final class ChangeDataSubscriber
{
    public function handleChangeData($event): void
    {
        $event->getImportStatus()->addCountRowsImport($event->getTypeRows(), $event->getCountRows());
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            ImportChangeReadDataEvent::class => 'handleChangeData',
            ImportChangeValidatedDataEvent::class => 'handleChangeData',
            ImportChangeDuplicatedDataEvent::class => 'handleChangeData',
            ImportChangeInsertedDataEvent::class => 'handleChangeData',
        ];
    }
}
