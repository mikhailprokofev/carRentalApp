<?php

declare(strict_types=1);

namespace App\Module\File\Handler\ImportCar;

use Generator;
use SplFileObject;

final class Handler
{
    public function handle(string $fileName): array
    {
        $resource = $this->createFileResource($fileName);

        $resourceGenerator = $this->getRows($resource);

        $result = $this->readByChunk($resourceGenerator);

        // TODO: здесь отправка в очередь
        return $result ?? [];
    }

    // TODO: нужен ли генератор для SplFileObject ?
    private function getRows(SplFileObject $resource): Generator
    {
        $resource->rewind();

        while ($resource->valid()) {
            yield $resource->fgetcsv();
        }

        // TODO: как закрыть SplFileObject ?
//        $resource = null;
    }

    private function createFileResource(string $fileName): SplFileObject
    {
        $resource = new SplFileObject($fileName, 'r');
        $resource->setFlags(
            SplFileObject::READ_CSV
            | SplFileObject::READ_AHEAD
            | SplFileObject::SKIP_EMPTY
            | SplFileObject::DROP_NEW_LINE
        );
        return $resource;
    }

    // TODO: не забыть считывание по чанкам
    private function readByChunk(Generator $resourceGenerator, int $chunk = 100): array
    {
        foreach ($resourceGenerator as $row) {
            $result[] = $row;
        }

        // TODO: массив Car maybe
        return $result ?? [];
    }
}
