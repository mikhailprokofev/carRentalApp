<?php

declare(strict_types=1);

namespace App\Module\Rate\Service;

enum RateCalculatingEnum: int
{
    case FOUR_DAY = 4;
    case NINE_DAY = 9;
    case SEVENTEEN_DAY = 17;
    case THIRTY_DAY = 30;
    case DEFAULT = 0;

    // TODO: возможно сделать интерфейс для enum или для сервиса (плюс о стратегии не забыть для сервиса)
    // TODO: подумать что делать с массивчиком + не забыть цикл рассчета в сервисе (или хелпере) + в сервисе (или хелпере) вохвращать Price

    public function calculatePercent(int $interval): array {
        return match(true) {
            $interval > RateCalculatingEnum::SEVENTEEN_DAY->value => [15, 10, 5, 0],
            $interval > RateCalculatingEnum::NINE_DAY->value => [10, 5, 0],
            $interval > RateCalculatingEnum::FOUR_DAY->value => [5, 0],
            default => [0],
        };
    }
}
