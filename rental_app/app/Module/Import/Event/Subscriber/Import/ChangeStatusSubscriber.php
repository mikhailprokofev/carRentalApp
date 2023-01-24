<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Subscriber\Import;

use App\Models\ImportStatus;
use App\Module\Import\Enum\ImportStatusEnum;
use App\Module\Import\Event\Model\Import\ImportStatusDoneEvent;
use App\Module\Import\Event\Model\Import\ImportStatusErrorEvent;
use App\Module\Import\Event\Model\Import\ImportStatusProgressEvent;
use App\Repository\ImportStatusRepositoryInterface;
use Illuminate\Events\Dispatcher;

final class ChangeStatusSubscriber
{
    public function __construct(
        private ImportStatusRepositoryInterface $importStatusRepository,
    ) {}

    public function handleDoneStatus(ImportStatusDoneEvent $event): void
    {
        $event->getImportStatus()->updateStatusImport(ImportStatusEnum::DONE);
    }

    public function handleErrorStatus(ImportStatusErrorEvent $event): void
    {
        if (is_null($importStatus = $event->getImportStatus())) {
            $importStatuses = $this->importStatusRepository->findByFileName($event->getFilename());

            $importStatus = $importStatuses->count()
                ? $importStatuses->first()
                : ImportStatus::initImport($event->getFilename());
        }

        $importStatus->updateStatusImport(ImportStatusEnum::ERROR);
    }

    public function handleProgressStatus(ImportStatusProgressEvent $event): void
    {
        $event->getImportStatus()->updateStatusImport(ImportStatusEnum::INPROGRESS);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            ImportStatusDoneEvent::class => 'handleDoneStatus',
            ImportStatusErrorEvent::class => 'handleErrorStatus',
            ImportStatusProgressEvent::class => 'handleProgressStatus',
        ];
    }
}
