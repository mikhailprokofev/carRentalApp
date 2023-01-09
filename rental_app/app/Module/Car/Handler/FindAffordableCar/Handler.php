<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\FindAffordableCar;

use App\Repository\CustomCarRepository;
use App\Repository\CustomCarRepositoryInterface;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

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
        $carId = $input->getCarId();

        // TODO: цепочка обязанностей
        $cars = $carId ? $this->findCar($carId, $input->getStartAt(), $input->getEndAt()) : null;

        $cars = is_null($cars) || $cars->isEmpty() ? $this->findCars($input->getStartAt(), $input->getEndAt()) : $cars;
        // TODO: цепочка обязанностей end

        return $this->makeOutput($cars);
    }

    // TODO: найти больше инфр о машинах
    private function makeOutput(Collection $cars): array
    {
        return $cars->map(fn($car) => $car->id)->toArray();
    }

    private function findCar(UuidInterface $carId, string $startAt, string $endAt): Collection
    {
        return $this->carRepository->findAffordableCarById($carId, $startAt, $endAt);
    }

    private function findCars(string $startAt, string $endAt): Collection
    {
        return $this->carRepository->findAffordableCars($startAt, $endAt);
    }
}
