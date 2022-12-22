<?php

namespace App\Jobs;

use Generator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SplFileObject;

// TODO: перенести в FileJob
class SendCarsReadingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $fileName,
    ) {}

    public function handle(): void
    {
        $resource = $this->createFileResource($this->fileName);

        $resourceGenerator = $this->getRows($resource);

        while ($resourceGenerator->valid()) {
            $result = $this->readByChunk($resourceGenerator);
            $this->requestRentalMicroService($result);
        }
    }

    // TODO: нужен ли генератор для SplFileObject ?
    private function getRows(SplFileObject $resource): Generator
    {
        $resource->rewind();

        while ($resource->valid()) {
            yield $resource->fgetcsv();
        }

        // TODO: как закрыть SplFileObject ?
        $resource = null;
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

    private function readByChunk(Generator $resourceGenerator, int $chunk = 100): array
    {
        while ($resourceGenerator->valid()) {
            if ($resourceGenerator->current()) {
                $result[] = $resourceGenerator->current();
                $chunk--;
            }

            $resourceGenerator->next();

            if ($chunk == 0) {
                break;
            }
        }

        // TODO: массив Car maybe
        return $result ?? [];
    }

    // TODO: maybe send DTO which can be serialized
    private function requestRentalMicroService(array $result): void
    {
        if (count ($result)) {
            TestJob::dispatch($result);
        }
    }
}