<?php

declare(strict_types=1);

namespace App\Module\File\Service\PrepareData;

interface PrepareDataServiceInterface
{
    public function prepareData(array $data): mixed;
}
