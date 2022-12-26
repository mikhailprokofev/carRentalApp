<?php

declare(strict_types=1);

namespace App\Module\Import\Service;

use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

final class InsertService
{
    private const EXCEPTION_MESSAGE = 'Unique violation';

    private function recursionInsert(array $data, Closure $commitData): void
    {
        try {
            $commitData($data);
        } catch (QueryException $e) {
            $message = $e->getMessage();
            if (str_contains($message, self::EXCEPTION_MESSAGE)) {
                foreach ($data as $key => $row) {
                    preg_match(
                        '/(\(cars_id[\w,\s]*\))(=)((\()([\w\d\-:+,\s]*)\))/',
                        $message,
                        $matches,
                        PREG_OFFSET_CAPTURE
                    );
                    $duplicatedValues = array_pop($matches)[0];
                    $duplicatedValues = explode(', ', $duplicatedValues);

                    if ($row['cars_id'] == $duplicatedValues[0]
                        && $row['rental_start'] == $duplicatedValues[1]
                        && $row['rental_end'] == $duplicatedValues[2]) {
                        // TODO: LOG INFO
                        unset($data[$key]);
                        break;
                    }
                }

                $this->recursionInsert($data);
            } else {
                Log::error($message);
            }
        }
    }

    public function commitData(array $data): void
    {
        if (count($data)) {
            $this->rentalRepository->insert($data);
        }
    }
}
