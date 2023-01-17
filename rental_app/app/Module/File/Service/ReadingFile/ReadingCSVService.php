<?php

declare(strict_types=1);

namespace App\Module\File\Service\ReadingFile;

use App\Module\File\Service\PrepareData\PrepareDataServiceInterface;
use Generator;
use SplFileObject;

final class ReadingCSVService implements ReadingFileServiceInterface
{
    public function getRows(SplFileObject $resource): Generator
    {
        $resource->rewind();

        while ($resource->valid()) {
            yield $resource->fgetcsv();
        }

        $resource = null;
    }

    public function createFileResource(string $fileName): SplFileObject
    {
        $resource = new SplFileObject($fileName, 'r');
        $resource->setFlags(
            SplFileObject::READ_CSV
            | SplFileObject::READ_AHEAD
            | SplFileObject::SKIP_EMPTY
            | SplFileObject::DROP_NEW_LINE,
        );

        return $resource;
    }

    public function readByChunk(
        Generator $resourceGenerator,
        PrepareDataServiceInterface $prepareDataService,
        int $chunk = 1,
    ): array {
        while ($resourceGenerator->valid()) {
            if ($resourceGenerator->current()) {
                $result[] = $prepareDataService->prepareData($resourceGenerator->current());
                $chunk--;
            }

            $resourceGenerator->next();

            if ($chunk == 0) {
                break;
            }
        }

        return $result ?? [];
    }

    public function deleteFile(string $fileName): void
    {
        unlink($fileName);
    }
}
