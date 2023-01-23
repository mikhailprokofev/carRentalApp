<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Models\ImportStatus;
use App\Module\Car\Enum\Country;
use App\Module\Import\Enum\ImportStatusEnum;
use App\Module\Import\Rule\CarDomainRules;
use App\Module\Import\Validator\DomainValidator;
use App\Repository\CustomCarRepository;
use App\Repository\CustomCarRepositoryInterface;
use App\Repository\ImportStatusRepository;
use App\Repository\ImportStatusRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

final class ImportCarsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private array $data;

    private string $fileName;

    private bool $isLast;

    private CustomCarRepositoryInterface $carRepository;

    private ImportStatusRepositoryInterface $importStatusRepository;

    private DomainValidator $validator;

    public function __construct(
        array $data,
        string $fileName,
        bool $isLast,
    ) {
        $this->data = $data;
        $this->fileName = $fileName;
        $this->isLast = $isLast;
        // TODO: вынести в di
        $this->carRepository = new CustomCarRepository();
        $this->importStatusRepository = new ImportStatusRepository();
        $this->validator = new DomainValidator(new CarDomainRules());
    }

    public function handle(): void
    {
        try {
            $rawData = $this->data;
            $result = [];

            $importStatus = $this->findOrCreateImport($this->fileName);
            $importStatus->addCountRowsImport('read_rows', count($rawData));
            $importStatus->updateStatusImport(ImportStatusEnum::INPROGRESS);

            foreach ($rawData as $row) {
                try {
                    $row = array_merge($row, [
                        'country' => Country::getByBrand($row['brand']),
                    ]);

                    $this->validator->validate($row, $importStatus);

                    $result[] = $row;
                } catch (ValidationException $exception) {
                    Log::error($exception->getMessage());
                }
            }

            $uniqueFieldValues = $this->makeUniqueValuesFromDB($result, 'number_plate', $importStatus);
            $data = $this->filterByUniqueField($result, $uniqueFieldValues, 'number_plate', $importStatus);

            $this->carRepository->insert($data);

            $importStatus->addCountRowsImport('inserted_rows', count($data));

            $this->doneImportStatus($importStatus);
        } catch (Exception $exception) {
            $this->turnErrorImportStatus(ImportStatusEnum::ERROR);
            throw $exception;
        }
    }

    private function filterByUniqueField(array $arr, array $arrUnique, string $field, ImportStatus $importStatus): array
    {
        foreach ($arr as $row) {
            if (in_array($row[$field], $arrUnique)) {
                $key = array_search($row[$field], $arrUnique);
                $data[$key] = $row;
            }
        }

        return $data ?? [];
    }

    private function makeUniqueValuesFromDB(array $arr, string $field, ImportStatus $importStatus): array
    {
        $uniqueFieldValues = array_column($arr, $field);

        $duplicateFieldValues = $this->carRepository->findDuplicateValues($uniqueFieldValues);

        $uniqueValues = array_diff($uniqueFieldValues, $duplicateFieldValues);

        $importStatus->addCountRowsImport('duplicated_rows', count($arr) - count($uniqueValues));

        return $uniqueValues;
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

    // TODO: events
    private function findOrCreateImport(string $filename): ImportStatus
    {
        $importStatuses = $this->importStatusRepository->findByFileName($filename);

        return $importStatuses->count() ? $importStatuses->first() : ImportStatus::initImport($filename);
    }
}
