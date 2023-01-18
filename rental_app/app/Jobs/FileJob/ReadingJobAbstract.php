<?php

declare(strict_types=1);

namespace App\Jobs\FileJob;

use App\Module\File\Service\PrepareData\PrepareDataServiceInterface;
use App\Module\File\Service\ReadingFile\ReadingFileServiceInterface;

abstract class ReadingJobAbstract
{
    protected string $fullFileName;

    protected PrepareDataServiceInterface $prepareDataService;

    protected ReadingFileServiceInterface $fileService;

    public function handle(): void
    {
        $resource = $this->fileService->createFileResource($this->fullFileName);

        $resourceGenerator = $this->fileService->getRows($resource);

        while ($resourceGenerator->valid()) {
            $result = $this->fileService->readByChunk($resourceGenerator, $this->prepareDataService);

            $isLast = !$resourceGenerator->valid();
            $this->requestToMicroService($result, $isLast);
        }

        $this->fileService->deleteFile($this->fullFileName);
    }

    abstract protected function requestToMicroService(array $result, bool $isLast = false): void;

    protected function deleteExt(string $fileName): string
    {
        return explode('.', $fileName)[0];
    }
}
