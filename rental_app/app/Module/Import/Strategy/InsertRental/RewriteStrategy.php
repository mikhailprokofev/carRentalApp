<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Models\ImportStatus;
use App\Module\Import\Enum\ImportStatusEnum;
use App\Module\Import\Rule\RentalDomainRules;
use App\Module\Import\Service\InsertService;
use App\Module\Import\Service\InsertServiceInterface;
use App\Module\Import\Validator\RentalDomainValidator;
use App\Repository\CustomCarRepository;
use App\Repository\CustomRentalRepository;
use App\Repository\CustomRentalRepositoryInterface;
use App\Repository\ImportStatusRepository;
use App\Repository\ImportStatusRepositoryInterface;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

final class RewriteStrategy implements InsertStrategyInterface
{
    private CustomRentalRepositoryInterface $rentalRepository;

    private InsertServiceInterface $insertService;

    private ImportStatusRepositoryInterface $importStatusRepository;

    private RentalDomainValidator $validator;

    public function __construct()
    {
        $this->insertService = new InsertService();
        $this->rentalRepository = new CustomRentalRepository();
        $this->importStatusRepository = new ImportStatusRepository();
        $this->validator = new RentalDomainValidator(new RentalDomainRules(new CustomCarRepository()));
    }

    public function import(array $data, string $filename): ImportStatus
    {
        // TODO: transaction
        $result = [];

        $isExistImportStatus = $this->existImportStatus($filename);

        $this->truncateDB($isExistImportStatus);

        $importStatus = $this->findOrCreateImport($filename);

        $importStatus->addCountRowsImport('read_rows', count($data));

        $importStatus->updateStatusImport(ImportStatusEnum::INPROGRESS);

        foreach ($data as $row) {
            try {
                $this->validator->validate($row, $importStatus);

                $result[] = $row;
            } catch (ValidationException $exception) {
                Log::error($exception->getMessage());
            }
        }

        $this->insertService->recursionInsert($result, $this->commitData($this->rentalRepository), $importStatus);

        return $importStatus;
    }

    private function commitData(CustomRentalRepositoryInterface $rentalRepository): Closure
    {
        return function ($data) use ($rentalRepository) {
            if (count($data)) {
                $rentalRepository->insert($data);
            }
        };
    }

    // TODO: events
    private function findOrCreateImport(string $filename): ImportStatus
    {
        $importStatuses = $this->importStatusRepository->findByFileName($filename);

        return $importStatuses->count() ? $importStatuses->first() : ImportStatus::initImport($filename);
    }

    private function truncateDB(bool $isExistImportStatus): void
    {
        if (!$isExistImportStatus) {
            $this->rentalRepository->truncate();
        }
    }

    private function existImportStatus(string $filename): bool
    {
        return $this->importStatusRepository->isExistByFileName($filename);
    }
}
