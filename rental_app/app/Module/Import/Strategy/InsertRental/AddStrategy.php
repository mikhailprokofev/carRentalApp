<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Models\ImportStatus;
use App\Module\Import\Enum\ImportStatusEnum;
use App\Module\Import\Rule\RentalDomainRules;
use App\Module\Import\Service\InsertService;
use App\Module\Import\Service\InsertServiceInterface;
use App\Module\Import\Validator\DomainValidator;
use App\Repository\CustomCarRepository;
use App\Repository\CustomRentalRepository;
use App\Repository\CustomRentalRepositoryInterface;
use App\Repository\ImportStatusRepository;
use App\Repository\ImportStatusRepositoryInterface;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

// TODO: дублирование стратегии (разница в транкейт)
// TODO: возможно то, что в импорт методе вынести в шаг + транкейт в шаг
final class AddStrategy implements InsertStrategyInterface
{
    private CustomRentalRepositoryInterface $rentalRepository;

    private InsertServiceInterface $insertService;

    private ImportStatusRepositoryInterface $importStatusRepository;

    private DomainValidator $validator;

    public function __construct()
    {
        $this->insertService = new InsertService();
        $this->rentalRepository = new CustomRentalRepository();
        $this->importStatusRepository = new ImportStatusRepository();
        $this->validator = new DomainValidator(new RentalDomainRules(new CustomCarRepository()));
    }

    public function import(array $data, string $filename): ImportStatus
    {
        $result = [];

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
}
