<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\FindAffordableCar;

use App\Repository\CarRepository;
use App\Repository\CarRepositoryInterface;
use function PHPUnit\Framework\isNull;

final class Handler
{
    private CarRepositoryInterface $carRepository;

    public function __construct(
        CarRepository $carRepository,
    ) {
        $this->carRepository = $carRepository;
    }

    public function handle(Input $input): array
    {
        $cars = null;

        if ($carId = $input->getCarId()) {
            $cars = $this->carRepository->findAffordableCarById($carId, $input->getStartAt(), $input->getEndAt());
        }

        if (isNull($cars) || $cars->isEmpty()) {
            $cars = $this->carRepository->findAffordableCars($input->getStartAt(), $input->getEndAt());
        }

        // TODO: найти машины из репозиотрия
        return $cars->map(fn($car) => $car->id)->toArray();
    }
}
