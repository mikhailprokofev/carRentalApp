<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Models\ImportStatus;
use App\Module\Import\Service\InsertService;
use App\Module\Import\Service\InsertServiceInterface;
use App\Repository\CustomRentalRepository;
use App\Repository\CustomRentalRepositoryInterface;
use Closure;

final class AddStrategy implements InsertStrategyInterface
{
    private CustomRentalRepositoryInterface $rentalRepository;

    private InsertServiceInterface $insertService;

    public function __construct()
    {
        $this->insertService = new InsertService();
        $this->rentalRepository = new CustomRentalRepository();
    }

    public function import(array $data, string $filename, bool $isLast): void
    {
        // TODO: проверка на доступность автомобиля
        $importStatus = new ImportStatus();
        $this->insertService->recursionInsert($data, $this->commitData($this->rentalRepository), $importStatus);
    }

    private function commitData(CustomRentalRepositoryInterface $rentalRepository): Closure
    {
        return function ($data) use ($rentalRepository) {
            if (count($data)) {
                $rentalRepository->insert($data);
            }
        };
    }
}
