<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\CheckAvailable;

use App\Repository\CustomCarRepository;
use App\Repository\CustomCarRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

final class Handler
{
    private CustomCarRepositoryInterface $carRepository;

    public function __construct(
        CustomCarRepository $carRepository,
    ) {
        $this->carRepository = $carRepository;
    }

    /**
     * @param Input $input
     * @return bool
     * @throws Exception
     */
    public function handle(Input $input): bool
    {
        $this->assertExistence($numberPlate = $input->getNumberPlate());

        $cars = $numberPlate ? $this->findCar($numberPlate, $input->getStartAt(), $input->getEndAt()) : null;

        return $this->makeOutput($cars->isEmpty());
    }

    private function makeOutput(bool $isEmpty): bool
    {
        return !$isEmpty;
    }

    /**
     * @param string $numberPlate
     * @return void
     * @throws Exception
     */
    private function assertExistence(string $numberPlate): void
    {
        if (!$this->carRepository->isExistByNumberPlate($numberPlate)) {
            throw new Exception('Car does not exist');
        }
    }

    private function findCar(string $numberPlate, string $startAt, string $endAt): Collection
    {
        return $this->carRepository->findAvailableCars($numberPlate, $startAt, $endAt);
    }
}
