<?php

declare(strict_types=1);

namespace App\Module\Rate\Chain\FindAffordable;

use App\Repository\CustomCarRepositoryInterface;
use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

final class FindCarSuccessor extends Successor
{
    private CustomCarRepositoryInterface $carRepository;

    public function __construct(
        CustomCarRepositoryInterface $carRepository,
        ?SuccessorInterface $successor,
    ) {
        $this->carRepository = $carRepository;
        parent::__construct($successor ?? new FindAllCarsSuccessor($this->carRepository));
    }

    public function process(string $startAt, string $endAt, ?UuidInterface $carId): ?Collection
    {
        $this->result = $this->carRepository->findAffordableCarById($carId, $startAt, $endAt);

        return parent::process($startAt, $endAt, $carId);
    }
}
