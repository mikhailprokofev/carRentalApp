<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Type: string
{
    use EnumToArray;

    case Econom = 'econom';
    case Business = 'business';
    case Luxury = 'luxury';

    private function cost(): ?int
    {
        return match($this) {
            self::Econom => 1000,
            self::Luxury => 5000,
            default => 0,
        };
    }

    private function priority(): int
    {
        return match($this) {
            self::Business => 1,
            self::Luxury => 2,
            default => 0,
        };
    }

    public static function maxCost(): int
    {
        return array_reduce(self::cases(), fn (int $max, self $case) => max($case->cost(), $max), 0);
    }

    public static function minCost(): int
    {
        return array_reduce(
            self::cases(),
            fn (int $min, self $case) => $case->cost() > 0 ? min($case->cost(), $min) : $min,
            PHP_INT_MAX,
        );
    }

    public function calculateCost(): int
    {
        return $this->cost() > 0 ? $this->cost() : $this->calculateCostByPriority();
    }

    public function calculateCostByPriority(): int
    {
        $max = self::maxCost();
        $min = self::minCost();

        $countChunks = (count(self::cases()) - 2) * 2;

        $costChunk = (int) round(($max - $min) / $countChunks);

        return $min + $costChunk * $this->priority();
    }
}
