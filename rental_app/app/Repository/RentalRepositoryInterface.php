<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;
use Ramsey\Uuid\UuidInterface;

interface RentalRepositoryInterface
{
    public function insert(array $data): void;

    public function update(array $data): void;

    public function truncate(): void;
}
