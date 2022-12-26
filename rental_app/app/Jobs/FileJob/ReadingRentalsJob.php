<?php

declare(strict_types=1);

namespace App\Jobs\FileJob;

use App\Jobs\RentalJob\ImportRentalJob;
use App\Module\File\Service\PrepareData\PrepareRentalDataService;
use App\Module\File\Service\ReadingFile\ReadingCSVService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ReadingRentalsJob extends ReadingJobAbstract implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $mode;

    public function __construct(
        string $fileName,
        string $mode,
    ) {
        $this->fileName = $fileName;
        $this->mode = $mode;
        // TODO: di
        $this->fileService = new ReadingCSVService();
        $this->prepareDataService = new PrepareRentalDataService();
    }

    protected function requestToMicroService(array $result): void
    {
        if (count($result)) {
            $test = new ImportRentalJob($result, $this->mode);
            $test->handle();
//            ImportRentalJob::dispatch($result, $this->mode);
        }
    }
}