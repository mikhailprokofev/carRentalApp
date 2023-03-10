<?php

declare(strict_types=1);

namespace App\Module\File\Handler\ImportCar;

use App\Jobs\FileJob\ReadingCarsJob;

final class Handler
{
    public function __construct(
        private string $directory = 'from di need',
    ) {}

    // TODO: ideas how to do without saving a file in controller
    public function handle(string $fileName): void
    {
        ReadingCarsJob::dispatch($fileName, storage_path('app') . '/' . $fileName);
    }
}
