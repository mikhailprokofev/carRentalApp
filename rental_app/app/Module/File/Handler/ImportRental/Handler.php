<?php

declare(strict_types=1);

namespace App\Module\File\Handler\ImportRental;

use App\Jobs\FileJob\ReadingRentalsJob;

final class Handler
{
    public function __construct(
        private string $directory = 'from di need',
    ) {
    }

    // TODO: ideas how to do without saving a file in controller
    public function handle(string $fileName, string $mode): void
    {
        ReadingRentalsJob::dispatch(storage_path('app') . '/' . $fileName, $mode);
    }
}
