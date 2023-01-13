<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\ListAvailable;

use App\Common\Type\Price;
use App\Repository\CustomCarRepositoryInterface;
use Illuminate\Support\Collection;

final class Handler
{
    public function __construct(
        private CustomCarRepositoryInterface $carRepository,
    ) {}

    public function handle(Input $input): array
    {
        $cars = $this->findCars($input->getStartAt(), $input->getEndAt());
        return $this->makeOutput($cars);
    }

    private function makeOutput(Collection $cars): array
    {
        return [
            'items' => $cars->map(fn($car) => [
                'number plate' => $car->number_plate,
                'model' => $car->model,
                'base salary' => (new Price($car->base_salary))->getApiValue(),
            ])->toArray(),
        ];
    }

    private function findCars(string $startAt, string $endAt): Collection
    {
        return $this->carRepository->findAvailableCars($startAt, $endAt);
    }
}
