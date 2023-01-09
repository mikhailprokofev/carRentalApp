<?php

declare(strict_types=1);

namespace App\Module\Import\Service;

use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

final class InsertService implements InsertServiceInterface
{
    private const EXCEPTION_MESSAGE = 'Unique violation';

    private const FORMAT_DUPLICATE_LOG = 'Duplicate row: car_id = %s, rental_start = %s, rental_end = %s';

    public function recursionInsert(array $data, Closure $commitData): void
    {
        try {
            $commitData($data);
        } catch (QueryException $e) {
            $message = $e->getMessage();
            if (str_contains($message, self::EXCEPTION_MESSAGE)) {
                $duplicatedValues = $this->detectDuplicateValues($message);

//                $data = array_filter($data, fn(array $row) => !$this->isDuplicatedRow($row, $duplicatedValues));

                Log::info(sprintf(self::FORMAT_DUPLICATE_LOG, ...$duplicatedValues));

                foreach ($data as $key => $row) {
                    if ($this->isDuplicatedRow($row, $duplicatedValues)) {
                        $this->deleteDuplicatedRow($data, $key);
                        break;
                    }
                }

                $this->recursionInsert($data, $commitData);
            } else {
                Log::error($message);
            }
        }
    }

    private function detectDuplicateValues(string $message): array
    {
        preg_match(
            '/(\(car_id[\w,\s]*\))(=)((\()([\w\-:+,\s]*)\))/',
            $message,
            $matches,
            PREG_OFFSET_CAPTURE,
        );

        $duplicatedValues = array_pop($matches)[0];

        return explode(', ', $duplicatedValues);
    }

    private function isDuplicatedRow(array $row, array $duplicatedValues): bool
    {
        return $row['car_id'] == $duplicatedValues[0]
            && $row['rental_start'] == $duplicatedValues[1]
            && $row['rental_end'] == $duplicatedValues[2];
    }

    private function deleteDuplicatedRow(array &$data, int $key): void
    {
        unset($data[$key]);
    }
}
