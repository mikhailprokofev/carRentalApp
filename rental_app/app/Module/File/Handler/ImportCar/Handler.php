<?php

declare(strict_types=1);

namespace App\Module\File\Handler\ImportCar;

use App\Jobs\FileJob\SendCarsReadingJob;

final class Handler
{
    // TODO: directory get from di, env
    public function __construct(
        private string $directory = '/storage/app',
    ) {}

    // TODO: ideas how to do without saving a file in controller
    public function handle(string $fileName): void
    {
        $test = new SendCarsReadingJob(storage_path('app') . '/' . $fileName);
        $test->handle();
//        SendCarsReadingJob::dispatch(storage_path('app') . '/' . $fileName);
    }
}
