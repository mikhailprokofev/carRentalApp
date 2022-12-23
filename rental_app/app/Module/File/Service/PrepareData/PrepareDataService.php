<?php

declare(strict_types=1);

namespace App\Module\File\Service\PrepareData;

final class PrepareDataService implements PrepareDataServiceInterface
{
    public function prepareData(mixed $data): mixed
    {
        return $data;
    }
}
