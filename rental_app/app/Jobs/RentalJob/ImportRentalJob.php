<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Models\ImportStatus;
use App\Module\Import\Enum\ImportStatusEnum;
use App\Module\Import\Enum\ModeImportEnum;
use App\Module\Import\Factory\ImportStrategyFactory;
use App\Repository\ImportStatusRepository;
use App\Repository\ImportStatusRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ImportRentalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;

    private ModeImportEnum $mode;

    private string $fileName;

    private bool $isLast;

    private ImportStrategyFactory $factory;

    private ImportStatusRepositoryInterface $importStatusRepository;

    public function __construct(
        array $data,
        string $mode,
        string $fileName,
        bool $isLast,
    ) {
        $this->data = $data;
        $this->mode = ModeImportEnum::tryFrom($mode);
        $this->fileName = $fileName;
        $this->isLast = $isLast;
        // TODO: вынести в di
        $this->factory = new ImportStrategyFactory();
        $this->importStatusRepository = new ImportStatusRepository();
    }

    public function handle(): void
    {
        try {
            $importStrategy = $this->factory->make($this->mode);
            $importStatus = $importStrategy->import($this->data, $this->fileName);

            if ($this->isLast) {
                $this->doneImportStatus($importStatus);
            }
        } catch (Exception $exception) {
            $this->turnErrorImportStatus(ImportStatusEnum::ERROR);
            throw $exception;
        }
    }

    // TODO: events
    private function turnErrorImportStatus(ImportStatusEnum $status): void
    {
        $importStatuses = $this->importStatusRepository->findByFileName($this->fileName);

        $importStatus = $importStatuses->count() ? $importStatuses->first() : ImportStatus::initImport($this->fileName);

        $importStatus->updateStatusImport($status);
    }

    private function doneImportStatus(ImportStatus $importStatus): void
    {
        $importStatus->updateStatusImport(ImportStatusEnum::DONE);
    }
}
