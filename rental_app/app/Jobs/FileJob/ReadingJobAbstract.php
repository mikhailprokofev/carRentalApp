<?php

declare(strict_types=1);

namespace App\Jobs\FileJob;

use App\Module\File\Service\PrepareData\PrepareDataServiceInterface;
use App\Module\File\Service\ReadingFile\ReadingFileServiceInterface;

abstract class ReadingJobAbstract
{
    protected string $fileName;

    protected PrepareDataServiceInterface $prepareDataService;

    protected ReadingFileServiceInterface $fileService;

    public function handle(): void
    {
        $resource = $this->fileService->createFileResource($this->fileName);

        $resourceGenerator = $this->fileService->getRows($resource);

        while ($resourceGenerator->valid()) {
            $result = $this->fileService->readByChunk($resourceGenerator, $this->prepareDataService);

            if ($resourceGenerator->valid()) {
                $this->requestToMicroService($result);
            } else {
                $this->requestToMicroService($result, true);
            }
        }

        $this->fileService->deleteFile($this->fileName);
    }

    abstract protected function requestToMicroService(array $result, bool $isLast = false): void;
}
