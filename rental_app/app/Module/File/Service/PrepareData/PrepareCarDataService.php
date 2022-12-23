<?php

declare(strict_types=1);

namespace App\Module\File\Service\PrepareData;

use App\Module\File\Entity\Car;

final class PrepareCarDataService implements PrepareDataServiceInterface
{
    public function prepareData(array $data): array
    {
        return (Car::make(...$data))->toArray();
    }
}
