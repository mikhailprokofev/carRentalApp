<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\ListAvailable;

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
        $cars = $this->findCars($input->getStartAt(), $input->getEndAt());
        return $this->makeOutput($cars);
    }

    private function makeOutput(Collection $cars): array
    {
        return [
            'items' => $cars->map(fn($car) => $car->number_plate)->toArray(),
        ];
    }

    private function findCars(string $startAt, string $endAt): Collection
    {
        return $this->carRepository->findAvailableCars($startAt, $endAt);
    }
}
