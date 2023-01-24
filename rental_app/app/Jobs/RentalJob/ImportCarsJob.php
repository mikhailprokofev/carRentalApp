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

            // TODO: транзакция
            while (is_null($importStatus = $this->findImportStatus())) {
                ImportInitEvent::dispatch($this->fileName);
            }

            ImportChangeReadDataEvent::dispatch($importStatus, count($rawData));
            ImportStatusProgressEvent::dispatch($importStatus);

            foreach ($rawData as $row) {
                try {
                    $row = array_merge($row, [
                        'country' => Country::getByBrand($row['brand']),
                    ]);

                    $this->validator->validate($row, $importStatus);
                    ImportChangeValidatedDataEvent::dispatch($importStatus, 1);

                    $result[] = $row;
                } catch (ValidationException $exception) {
                    Log::error($exception->getMessage());
                }
            }

            $uniqueFieldValues = $this->makeUniqueValuesFromDB($result, 'number_plate', $importStatus);
            $data = $this->filterByUniqueField($result, $uniqueFieldValues, 'number_plate');

            $this->carRepository->insert($data);

            ImportChangeInsertedDataEvent::dispatch($importStatus, count($data));
            ImportStatusDoneEvent::dispatch($importStatus);
        } catch (Exception $exception) {
            ImportStatusErrorEvent::dispatch($importStatus ?? null);
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
}
