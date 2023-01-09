<?php

declare(strict_types=1);

namespace App\Module\File\Service\ReadingFile;

use App\Module\File\Service\PrepareData\PrepareDataServiceInterface;
use Generator;
use SplFileObject;

interface ReadingFileServiceInterface
{
    public function getRows(SplFileObject $resource): Generator;

    public function createFileResource(string $fileName): SplFileObject;

    public function readByChunk(
        Generator $resourceGenerator,
        PrepareDataServiceInterface $prepareDataService,
        int $chunk = 100
    ): array;

    public function deleteFile(string $fileName): void;
}
