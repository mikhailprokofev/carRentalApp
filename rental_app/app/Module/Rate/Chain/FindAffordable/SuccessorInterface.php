<?php

declare(strict_types=1);

namespace App\Module\Rate\Chain\FindAffordable;

use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface SuccessorInterface
{
    public function process(string $startAt, string $endAt, ?UuidInterface $carId): ?Collection;
}
