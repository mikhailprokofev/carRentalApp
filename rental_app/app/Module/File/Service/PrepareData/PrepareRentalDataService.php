<?php

declare(strict_types=1);

namespace App\Module\File\Service\PrepareData;

use App\Module\File\Entity\Rental;

final class PrepareRentalDataService implements PrepareDataServiceInterface
{
    public function prepareData(array $data): array
    {
        return (Rental::make(...$data))->toArray();
    }
}
