<?php

declare(strict_types=1);

namespace App\Module\File\Service\PrepareData;

use App\Module\File\Entity\CarDTO;

final class PrepareCarDataService implements PrepareDataServiceInterface
{
    public function prepareData(array $data): array
    {
        return (CarDTO::make(...$data))->toArray();
    }
}
