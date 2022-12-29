<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Module\Import\Service\InsertService;
use App\Module\Import\Service\InsertServiceInterface;
use App\Repository\CustomCustomRentalRepository;
use App\Repository\CustomRentalRepositoryInterface;
use Closure;

final class AddStrategy implements InsertStrategyInterface
{
    private CustomRentalRepositoryInterface $rentalRepository;
    private InsertServiceInterface $insertService;

    public function __construct()
    {
        $this->insertService = new InsertService();
        $this->rentalRepository = new CustomCustomRentalRepository();
    }

    public function import(array $data): void
    {
        // TODO: проверка на доступность автомобиля
        $this->insertService->recursionInsert($data, $this->commitData($this->rentalRepository));
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
