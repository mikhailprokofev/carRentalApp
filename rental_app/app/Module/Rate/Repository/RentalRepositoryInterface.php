<?php

declare(strict_types=1);

namespace App\Module\Rate\Repository;

interface RentalRepositoryInterface
{
    public function insert(array $data): void;

    public function update(array $data): void;

    public function truncate(): void;
}
