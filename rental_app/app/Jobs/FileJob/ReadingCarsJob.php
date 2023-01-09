<?php

declare(strict_types=1);

namespace App\Jobs\FileJob;

use App\Jobs\RentalJob\ImportCarsJob;
use App\Module\File\Service\PrepareData\PrepareCarDataService;
use App\Module\File\Service\ReadingFile\ReadingCSVService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ReadingCarsJob extends ReadingJobAbstract implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        string $fileName,
    ) {
        $this->fileName = $fileName;
        // TODO: di
        $this->fileService = new ReadingCSVService();
        $this->prepareDataService = new PrepareCarDataService();
    }

    protected function requestToMicroService(array $result): void
    {
        if (count($result)) {
            ImportCarsJob::dispatch($result);
        }
    }
}
