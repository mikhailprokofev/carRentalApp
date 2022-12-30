<?php

declare(strict_types=1);

namespace App\Module\Report\Load\Cars;

use App\Http\Resources\ReportLoadCarsResource;
use App\Repository\CustomCustomRentalRepository;
use App\Repository\CustomRentalRepositoryInterface;
use DateTimeImmutable;
use Illuminate\Support\Collection;

final class Handler
{
    private CustomRentalRepositoryInterface $rentalRepository;

    public function __construct(
        CustomCustomRentalRepository $rentalRepository,
    ) {
        $this->rentalRepository = $rentalRepository;
    }

    public function handle(Input $input): array
    {
        $year = $input->getYear();
        $month = $input->getMonth();

        $data = $this->rentalRepository->findLoadCarsInfo($year, $month);

        $lastDay = $this->calculateLastMonthDay($year, $month);

        return $this->makeOutput($data, $lastDay);
    }

    private function makeOutput(Collection $data, int $lastDay): array
    {
        $output['list'] = $this->prepareList($data, $lastDay);
        $output['total_load'] = $this->calculateTotal($output['list']);
        return $output;
    }

    private function prepareList(Collection $data, int $lastDay): array
    {
        $result = $data->map(
            function ($car) use($lastDay) {
                if ($car->number_plate) {
                    return [
                        'number_plate' => $car->number_plate,
                        'load' => round($car->diff ? $car->diff / $lastDay * 100 : 0, 2), // TODO вынести в метод
                    ];
                }
                return [];
            }
        );

        return $result->filter(fn (array $car) => count($car))->toArray();
    }

    private function calculateLastMonthDay(int $year, int $month): int
    {
        $date = (new DateTimeImmutable("$year-$month-01"))->format('Y-m-t');
        return (int) date('d', strtotime($date));
    }

    private function calculateTotal(array $data): float
    {
        $total = array_reduce(
            $data,
            function (float $total, array $car) {
                $total += $car['load'];
                return $total;
            },
            0
        );

        return $total ? round($total / count($data), 2) : 0;
    }
}
