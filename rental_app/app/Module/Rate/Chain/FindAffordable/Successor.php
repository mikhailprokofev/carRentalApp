<?php

declare(strict_types=1);

namespace App\Module\Rate\Chain\FindAffordable;

use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

class Successor implements SuccessorInterface
{
    protected ?Collection $result = null;

    public function __construct(
        protected ?SuccessorInterface $successor,
    ) {}

//    public function handle(string $startAt, string $endAt, ?UuidInterface $carId): ?Collection
//    {
//        if
//    }

    public function process(string $startAt, string $endAt, ?UuidInterface $carId): ?Collection
    {
        if (is_null($this->result) || $this->result->isEmpty()) {
            if ($this->successor) {
                return $this->successor->process($startAt, $endAt, $carId);
            }
        }

        return $this->result;
    }
}
