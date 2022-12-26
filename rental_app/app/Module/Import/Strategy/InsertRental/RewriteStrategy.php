<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Module\Rate\Repository\RentalRepository;
use App\Module\Rate\Repository\RentalRepositoryInterface;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class RewriteStrategy implements InsertStrategyInterface
{
    private RentalRepositoryInterface $rentalRepository;

    public function __construct()
    {
        $this->rentalRepository = new RentalRepository();
    }

    public function import(array $data): void
    {
//        try {
//            DB::transaction(function() {
//                // ... снова и снова
//            }, 3);  // Повторить три раза, прежде чем признать неудачу
//        } catch (ExternalServiceException $exception) {
//            return 'Извините, внешняя служба не работает, а вы не сможете завершить регистрацию без ключей от нее'.
//        }

        DB::beginTransaction();
        try {
            $this->rentalRepository->truncate();
            // TODO: проверка на доступность автомобиля
            $this->rentalRepository->update($data);
            DB::commit();
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }
}
