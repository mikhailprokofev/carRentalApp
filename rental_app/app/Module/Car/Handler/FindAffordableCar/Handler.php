<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\FindAffordableCar;

use App\Repository\RentalRepository;

final class Handler
{
    public function __construct(
        private RentalRepository $rentalRepository,
    ) {}

    public function handle(Input $input): array
    {
//        if ($carId = $input->getCarId()) {
//            $rental = $this->rentalRepository->findLastRentalByCar($carId);
//            if ($rental ? $this->isAccessCar($rental) : true) {
//                // find car + return []
//            }
//        }
        dd($this->rentalRepository->find($input->getCarId(), '2022-12-12', '2022-12-24'));
    }

    private function isAccessCar(): bool
    {
        return true;
    }
}
