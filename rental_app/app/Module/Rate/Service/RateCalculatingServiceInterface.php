<?php

declare(strict_types=1);

namespace App\Module\Rate\Service;

interface RateCalculatingServiceInterface
{
    public function calculate(int $interval, int|null $rate, string $strategy): int|null;
}
