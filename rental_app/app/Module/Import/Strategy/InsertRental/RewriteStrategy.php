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

        $importStatus = $this->findOrCreateImport($filename);

        $importStatus->addCountRowsImport('read_rows', count($data));

        $this->rentalRepository->truncate();

        $importStatus->updateStatusImport(ImportStatusEnum::INPROGRESS);

        // TODO: валидацию вынести куда-нибудь
        foreach ($data as $el) {
            try {
                $validator = Validator::make($el, RentalDomainRules::rules());
                $validator->validated(); // //        if ($validator->fails()) {

                $importStatus->addCountRowsImport('validated_rows', 1);

                $result[] = $el;
            } catch (ValidationException $exception) {
                Log::error($exception->getMessage());
            }
        }

        $this->insertService->recursionInsert($result, $this->commitData($this->rentalRepository));

        // TODO: минус дубликаты
        $importStatus->addCountRowsImport('inserted_rows', count($result));

        if ($isLast) {
            $importStatus->updateStatusImport(ImportStatusEnum::DONE);
        }

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
    private function findOrCreateImport(string $filename): ImportStatus
    {
        $importStatuses = $this->importStatusRepository->findByFileName($filename);

        return $importStatuses->count() ? $importStatuses->first() : ImportStatus::initImport($filename);
    }
}
