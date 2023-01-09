<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\FindAffordableCar;

use App\Repository\CustomCarRepository;
use App\Repository\CustomCarRepositoryInterface;

final class Handler
{
    private CustomCarRepositoryInterface $carRepository;

    public function __construct(
        CustomCarRepository $carRepository,
    ) {
        $this->carRepository = $carRepository;
    }

    public function handle(Input $input): array
    {
        $cars = null;

        if ($carId = $input->getCarId()) {
            $cars = $this->carRepository->findAffordableCarById($carId, $input->getStartAt(), $input->getEndAt());
        }

        if (is_null($cars) || $cars->isEmpty()) {
            $cars = $this->carRepository->findAffordableCars($input->getStartAt(), $input->getEndAt());
        }

        // TODO: найти машины из репозиотрия
        return $cars->map(fn ($car) => $car->id)->toArray();
    }
}
