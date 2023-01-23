<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\ListAvailable;

use App\Module\Rate\Service\RateCalculatingServiceInterface;
use App\Module\Rate\Service\RateCalculatingService;
use App\Repository\CustomCarRepositoryInterface;
use Illuminate\Support\Collection;
use App\Common\Type\Price;

final class Handler
{
    public function __construct(
        private CustomCarRepositoryInterface $carRepository,
        private RateCalculatingServiceInterface $rateService,
    ) {}

    public function handle(Input $input): array
    {
        $start = new \DateTimeImmutable($input->getStartAt());
        $end = new \DateTimeImmutable($input->getEndAt());
        $dateInterval = date_diff($end,$start)->days + 1;
        $minRate = $this->rateService->calculate($dateInterval, $input->getMinSalary(),'into');
        $maxRate = $this->rateService->calculate($dateInterval, $input->getMaxSalary(),'into');

        $cars = $this->findCars(
            $input->getStartAt(),
            $input->getEndAt(),
            $minRate,
            $maxRate,
        );

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

    private function findCars(string $startAt, string $endAt, ?int $min, ?int $max): Collection
    {
        return $this->carRepository->withFilters($startAt, $endAt, $min, $max);
    }
}
