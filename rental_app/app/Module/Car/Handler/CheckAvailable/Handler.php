<?php

declare(strict_types=1);

namespace App\Module\Car\Handler\CheckAvailable;

use App\Repository\CustomCarRepositoryInterface;
use DomainException;
use Illuminate\Support\Collection;

final class Handler
{
    public function __construct(
        private CustomCarRepositoryInterface $carRepository,
    ) {}

    /**
     * @param Input $input
     * @return bool
     * @throws DomainException
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
     * @throws DomainException
     */
    private function assertExistence(string $numberPlate): void
    {
        if (!$this->carRepository->isExistByNumberPlate($numberPlate)) {
            throw new DomainException('Car does not exist');
        }
    }

    private function findCar(string $numberPlate, string $startAt, string $endAt): Collection
    {
        return $this->carRepository->findAvailableCarByNumberPlate($numberPlate, $startAt, $endAt);
    }
}
