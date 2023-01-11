<?php

declare(strict_types=1);

namespace App\Module\Report\Load\Cars;

use App\Repository\CustomRentalRepository;
use App\Repository\CustomRentalRepositoryInterface;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

final class Handler
{
    private CustomRentalRepositoryInterface $rentalRepository;
    private int $timeStore;

    public function __construct(
        CustomRentalRepository $rentalRepository,
        ?int $timeStore = 1,
    ) {
        $this->rentalRepository = $rentalRepository;
        $this->timeStore = $timeStore;
    }

    public function handle(Input $input): array
    {
        $year = $input->getYear();
        $month = $input->getMonth();

        return $this->makeOutput($year, $month);
    }

    private function makeReport(Collection $data, int $lastDay): array
    {
        $output['list'] = $this->prepareList($data, $lastDay);
        $output['total_load'] = $this->calculateTotalLoad($output['list']);

        return $output;
    }

    private function prepareList(Collection $data, int $lastDay): array
    {
        foreach ($data->toArray() as $car) {
            if ($car->number_plate) {
                $result[] = [
                    'number_plate' => $car->number_plate,
                    'load' => $this->calculateCarLoad($lastDay, $car->diff),
                ];
            }
        }

        return $result ?? [];
    }

    private function calculateLastMonthDay(int $year, int $month): int
    {
        $date = (new DateTimeImmutable("$year-$month-01"))->format('Y-m-t');

        return (int) date('d', strtotime($date));
    }

    private function calculateTotalLoad(array $data): float
    {
        $total = array_reduce(
            $data,
            function (float $total, array $car) {
                $total += $car['load'];

                return $total;
            },
            0,
        );

        return $total ? round($total / count($data), 2) : 0;
    }

    private function calculateCarLoad(int $lastDay, ?string $diff): float
    {
        return round((int) $diff ? (int) $diff / $lastDay * 100 : 0, 2);
    }

    private function makeOutput(int $year, int $month): array
    {
        if (!$report = $this->getReportFromCache($year, $month)) {
            $data = $this->rentalRepository->findLoadCarsInfo($year, $month);

            $lastDay = $this->calculateLastMonthDay($year, $month);

            $report = $this->makeReport($data, $lastDay);

            $this->storeReportToCache($report, $year, $month);
        }
dd(Cache::tags('report_load')->get($year . $month));
        return $report;
    }

    /**
     * @return array|null
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getReportFromCache(int $year, int $month): ?array
    {
        return Cache::has($year . $month) ? Cache::tags('report_load')->get($year . $month) : null;
    }

    private function storeReportToCache(array $data, int $year, int $month): void
    {
//        Cache::remember('report_load', $this->timeStore, function () use ($data) {
//            return $data;
//        });
//Redis::
        Cache::tags('report_load')->put($year . $month, $data, $this->timeStore);
    }
}
