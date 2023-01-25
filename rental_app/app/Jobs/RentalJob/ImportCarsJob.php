<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Common\Utility\LeaverUniqueData;
use App\Models\ImportStatus;
use App\Module\Car\Enum\Country;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeDuplicatedDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeInsertedDataEvent;
use App\Module\Import\Event\Model\Import\ChangeData\ImportChangeReadDataEvent;
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
use Closure;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
            // TODO: транзакция
            $importStatus = $this->findOrCreateImportStatus();

            ImportChangeReadDataEvent::dispatch($importStatus, count($this->data));
            ImportStatusProgressEvent::dispatch($importStatus);

            $data = $this->validator->softValidate($this->data, $importStatus, $this->prepareDataForValidation());
            $data = $this->reachUniqueData($data, 'number_plate', $importStatus);
            $this->insertData($data);

            ImportChangeInsertedDataEvent::dispatch($importStatus, count($data));
            ImportStatusDoneEvent::dispatch($importStatus);
        } catch (Exception $exception) {
            ImportStatusErrorEvent::dispatch($importStatus ?? null, $this->fileName);

            throw $exception;
        }
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

    private function prepareDataForValidation(): Closure
    {
        return function ($data) {
            return array_merge($data, [
                'country' => Country::getByBrand($data['brand']),
            ]);
        };
    }

    private function reachUniqueData(array $data, string $field, ImportStatus $importStatus): array
    {
        $uniqueDataFromDB = LeaverUniqueData::leaveUniqueValuesByDB($this->carRepository, $data, $field);
        $result = LeaverUniqueData::leaveUniqueValuesFromArrays($uniqueDataFromDB, $data, $field);

        ImportChangeDuplicatedDataEvent::dispatch($importStatus, count($data) - count($result));

        return $result;
    }

    private function insertData(array $data): void
    {
        $this->carRepository->insert($data);
    }
}
