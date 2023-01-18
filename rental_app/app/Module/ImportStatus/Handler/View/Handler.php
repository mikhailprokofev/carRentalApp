<?php

declare(strict_types=1);

namespace App\Module\ImportStatus\Handler\View;

use App\Common\Output\Output;
use App\Models\ImportStatus;
use App\Repository\ImportStatusRepositoryInterface;

final class Handler
{
    public function __construct(
        private ImportStatusRepositoryInterface $importStatusRepository,
    ) {}

    public function handle(Input $input): Output
    {
        $this->assertExistenceImportStatus($input->getFileName());

        $importStatus = $this->findImportStatus($input->getFileName());

        return $this->makeOutput($importStatus);
    }

    private function findImportStatus(string $filename): ImportStatus
    {
        return $this->importStatusRepository->findByFileName($filename)->first();
    }

    private function assertExistenceImportStatus(string $filename): void
    {
        if (!$this->importStatusRepository->isExistByFileName($filename)) {
            throw new \DomainException('There is no exist import status');
        }
    }

    private function makeOutput(ImportStatus $importStatus): Output
    {
        return (new Output())
            ->set('id', $importStatus->id)
            ->set('filename', $importStatus->filename)
            ->set('status', $importStatus->status)
            ->set('read_rows', $importStatus->read_rows)
            ->set('validated_rows', $importStatus->validated_rows)
            ->set('duplicated_rows', $importStatus->duplicated_rows)
            ->set('inserted_rows', $importStatus->inserted_rows);
    }
}
