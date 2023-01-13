<?php

declare(strict_types=1);

namespace App\Module\Report\Load\Cars;

use App\Common\Output\Output;
use App\Common\Utility\CalculatorDate;
use App\Common\Utility\CalculatorPercent;
use App\Module\Cache\Strategy\RedisCacheInterfaceStrategy;
use App\Repository\CustomRentalRepositoryInterface;
use Illuminate\Support\Collection;

final class Handler
{
    private CustomRentalRepositoryInterface $rentalRepository;
    private RedisCacheInterfaceStrategy $redisCacheStrategy;
    private int $timeStore;

    public function __construct(
        CustomRentalRepositoryInterface $rentalRepository,
        RedisCacheInterfaceStrategy $redisCacheStrategy,
        ?int $timeStore = 7200, // in seconds
    ) {
        $this->rentalRepository = $rentalRepository;
        $this->redisCacheStrategy = $redisCacheStrategy;
        $this->timeStore = $timeStore;
    }

    public function handle(Input $input): Output
    {
        $year = $input->getYear();
        $month = $input->getMonth();

        return $this->makeOutput($year, $month);
    }

    private function makeReport(Collection $data, int $lastDay): Output
    {
        $list = $this->prepareCarList($data, $lastDay);

        return (new Output())
            ->set('list', $list)
            ->set('total_load', $this->prepareTotalLoad($list));
    }

    private function prepareCarList(Collection $cars, int $lastDay): array
    {
        $list = array_map(fn (object $car) => $this->prepareCar($car, $lastDay), $cars->toArray());

        return array_filter($list, fn(array $car) => count($car));
    }

    private function prepareCar(object $car, int $lastDay): array
    {
        return $car->number_plate ? [
            'number_plate' => $car->number_plate,
            'load' => $this->calculateCarLoad($lastDay, $car->diff ? (int) $car->diff : 0),
        ] : [];
    }

    private function prepareTotalLoad(array $loadCars): float
    {
        $total = array_reduce($loadCars, fn (float $total, array $loadCar) => $total + $loadCar['load'], 0);

        return CalculatorPercent::calculate($total, count($loadCars));
    }

    private function calculateCarLoad(int $monthLastDay, int $diff): float
    {
        return CalculatorPercent::calculate($monthLastDay, $diff);
    }

    private function calculateMonthLastDay(int $year, int $month): int
    {
        return CalculatorDate::calculateMonthLastDay($year, $month);
    }

    private function makeOutput(int $year, int $month): Output
    {
        if (!$report = $this->getReportFromCache($year, $month)) {
            $data = $this->rentalRepository->findLoadCarsInfo($year, $month);

            $monthLastDay = $this->calculateMonthLastDay($year, $month);

            $report = $this->makeReport($data, $monthLastDay);

            $this->storeReportToCache($report->getAll(), $year, $month);

            return $report;
        } else {
            return (new Output())
                ->setCollection($report);
        }
    }

    private function getReportFromCache(int $year, int $month): ?array
    {
        return $this->redisCacheStrategy->getCacheValue($year . $month);
    }

    private function storeReportToCache(array $data, int $year, int $month): void
    {
        $this->redisCacheStrategy->setCacheValue($year . $month, $data, $this->timeStore * 60);
    }
}
