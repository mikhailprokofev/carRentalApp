<?php

declare(strict_types=1);

namespace App\Module\Import\Strategy\InsertRental;

use App\Module\Rate\Repository\RentalRepository;
use App\Module\Rate\Repository\RentalRepositoryInterface;

final class AddStrategy implements InsertStrategyInterface
{
    private RentalRepositoryInterface $rentalRepository;

    public function __construct()
    {
        $this->rentalRepository = new RentalRepository();
    }

    public function import(array $data): void
    {
        // TODO: проверка на доступность автомобиля
        $this->recursionInsert($this->data);
    }

    private function commitData(array $data): void
    {
        if (count($data)) {
            $this->rentalRepository->insert($data);
        }
    }
}
