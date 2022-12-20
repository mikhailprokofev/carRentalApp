<?php

declare(strict_types=1);

namespace App\Module\Rate\Service;

use App\Common\Type\Price;

interface RateCalculatingServiceInterface
{
    public function calculate(int $interval, int $baseRate): Price;
}
