<?php

declare(strict_types=1);

namespace App\Module\File\Handler\ImportCar;

use Exception;
use Generator;

final class Handler
{
    public function handle(string $fileName): array
    {
        if (!$stream = fopen($fileName, 'r')) {
            throw new Exception('Cannot open the file');
        }

        foreach ($this->getRows($stream) as $row) {
            $result[] = $row;
        }

        return $result ?? [];
    }

    private function getRows(mixed $stream): Generator
    {
        while (!feof($stream)) {
            yield fgetcsv($stream);
        }

        fclose($stream);
    }
}
