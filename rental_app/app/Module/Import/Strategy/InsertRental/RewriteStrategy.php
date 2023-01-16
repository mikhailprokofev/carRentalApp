<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Models\ImportStatus;
use App\Module\Import\Enum\ImportStatusEnum;
use App\Module\Import\Rule\RentalDomainRules;
use App\Module\Import\Service\InsertService;
use App\Module\Import\Service\InsertServiceInterface;
use App\Repository\CustomRentalRepository;
use App\Repository\CustomRentalRepositoryInterface;
use App\Repository\ImportStatusRepository;
use App\Repository\ImportStatusRepositoryInterface;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class RewriteStrategy implements InsertStrategyInterface
{
    private CustomRentalRepositoryInterface $rentalRepository;

    private InsertServiceInterface $insertService;

    private ImportStatusRepositoryInterface $importStatusRepository;

    public function __construct()
    {
        $this->insertService = new InsertService();
        $this->rentalRepository = new CustomRentalRepository();
        $this->importStatusRepository = new ImportStatusRepository();
    }

    public function import(array $data, string $filename, bool $isLast): void
    {
//        try {
//            DB::transaction(function() {
//                // ... снова и снова
//            }, 3);  // Повторить три раза, прежде чем признать неудачу
//        } catch (ExternalServiceException $exception) {
//            return 'Извините, внешняя служба не работает, а вы не сможете завершить регистрацию без ключей от нее'.
//        }

//        try {
//            DB::beginTransaction();

        $result = [];

        $importStatus = $this->findOrCreateImport($data, $filename);

        $this->rentalRepository->truncate();

        $importStatus = $this->updateStatusImport($importStatus, ImportStatusEnum::INPROGRESS);

        // TODO: валидацию вынести куда-нибудь
        foreach ($data as $el) {
            try {
                $validator = Validator::make($el, RentalDomainRules::rules());
                $validator->validated(); // //        if ($validator->fails()) {

                $importStatus = $this->addCountRowsImport($importStatus, 'validated_rows', 1);

                $result[] = $el;
            } catch (ValidationException $exception) {
                Log::error($exception->getMessage());
            }
        }

        $this->insertService->recursionInsert($result, $this->commitData($this->rentalRepository));

        // TODO: минус дубликаты
        $importStatus = $this->addCountRowsImport($importStatus, 'inserted_rows', count($result));

        if ($isLast) {
            $importStatus = $this->updateStatusImport($importStatus, ImportStatusEnum::DONE);
        }

        // TODO: доб÷вить error status


//            DB::commit();
//        } catch (QueryException $e) {
//            Log::error($e->getMessage());
//            DB::rollBack();
//        }
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
    private function makeImport(string $filename, int $readRows): ImportStatus
    {
        return ImportStatus::create([
            'status' => ImportStatusEnum::BEGIN->value,
            'filename' => $filename,
            'read_rows' => $readRows,
        ]);
    }

    private function updateStatusImport(ImportStatus $importStatus, ImportStatusEnum $status): ImportStatus
    {
        $importStatus->setAttribute('status', $status->value);
        $importStatus->save();
        return $importStatus;
    }

    private function addCountRowsImport(ImportStatus $importStatus, string $field, int $count): ImportStatus
    {
        $count += $importStatus->getAttributeValue($field);
        $importStatus->setAttribute($field, $count);
        $importStatus->save();
        return $importStatus;
    }

    private function findOrCreateImport(array $data, string $filename): ImportStatus
    {
        $importStatus = $this->importStatusRepository->findByFileName($filename);

        $readRows = count($data);

        if ($importStatus->count()) {
            $importStatus = $importStatus->first();
            $importStatus = $this->addCountRowsImport($importStatus, 'read_rows', $readRows);
        } else {
            $importStatus = $this->makeImport($filename, $readRows);
        }

        return $importStatus;
    }
}
