<?php

declare(strict_types=1);

namespace App\Module\Rate\Chain\FindAffordable;

use App\Repository\CustomCarRepositoryInterface;
use Illuminate\Support\Collection;

final class FindAllCarsSuccessor extends Successor
{
    private CustomCarRepositoryInterface $carRepository;

    public function __construct(
        CustomCarRepositoryInterface $carRepository,
        ?SuccessorInterface $successor,
    ) {
        $this->carRepository = $carRepository;
        parent::__construct($successor ?? null);
    }

    public function process(string $startAt, string $endAt): ?Collection
    {
        $this->result = $this->carRepository->findAffordableCars($startAt, $endAt);;

        return parent::process($startAt, $endAt);
    }
}
