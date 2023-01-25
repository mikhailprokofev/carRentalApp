<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Models\ImportStatus;
use App\Module\Car\Enum\Country;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeDuplicatedDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeInsertedDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeReadDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeValidatedDataEvent;
use App\Module\Import\Event\Model\Import\ChangeStatus\ImportStatusDoneEvent;
use App\Module\Import\Event\Model\Import\ChangeStatus\ImportStatusErrorEvent;
use App\Module\Import\Event\Model\Import\ChangeStatus\ImportStatusProgressEvent;
use App\Module\Import\Event\Model\Import\Init\ImportInitEvent;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

    /**
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $rawData = $this->data;

            // TODO: транзакция
            $importStatus = $this->findOrCreateImportStatus();

            ImportChangeReadDataEvent::dispatch($importStatus, count($rawData));
            ImportStatusProgressEvent::dispatch($importStatus);

            $data = $this->dataSoftValidate($rawData, $importStatus);
            $data = $this->reachUniqueData($data, $importStatus);
            $this->insertData($data);

            ImportChangeInsertedDataEvent::dispatch($importStatus, count($data));
            ImportStatusDoneEvent::dispatch($importStatus);
        } catch (Exception $exception) {
            ImportStatusErrorEvent::dispatch($importStatus ?? null, $this->fileName);

            throw $exception;
        }
    }

    private function filterByUniqueField(array $arr, array $arrUnique, string $field): array
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

        ImportChangeDuplicatedDataEvent::dispatch($importStatus, count($arr) - count($uniqueValues));

        return $uniqueValues;
    }

    private function findImportStatus(): ?ImportStatus
    {
        $importStatuses = $this->importStatusRepository->findByFileName($this->fileName);

        return $importStatuses->count() ? $importStatuses->first() : null;
    }

    private function findOrCreateImportStatus(): ImportStatus
    {
        while (is_null($importStatus = $this->findImportStatus())) {
            ImportInitEvent::dispatch($this->fileName);
        }

        return $importStatus;
    }

    private function dataSoftValidate(array $data, ImportStatus $importStatus): array
    {
        foreach ($data as $row) {
            try {
                $row = $this->prepareDataForValidation($row);

                $this->validator->validate($row);
                ImportChangeValidatedDataEvent::dispatch($importStatus, 1);

                $result[] = $row;
            } catch (ValidationException $exception) {
                Log::error($exception->getMessage());
            }
        }

        return $result ?? [];
    }

    private function prepareDataForValidation(array $data): array
    {
        return array_merge($data, [
            'country' => Country::getByBrand($data['brand']),
        ]);
    }

    private function reachUniqueData(array $data, ImportStatus $importStatus): array
    {
        $uniqueFieldValues = $this->makeUniqueValuesFromDB($data, 'number_plate', $importStatus);
        return $this->filterByUniqueField($data, $uniqueFieldValues, 'number_plate');
    }

    private function insertData(array $data): void
    {
        $this->carRepository->insert($data);
    }
}
