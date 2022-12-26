<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Module\Import\Enum\ModeImportEnum;
use App\Module\Rate\Repository\RentalRepository;
use App\Module\Rate\Repository\RentalRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ImportRentalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const EXCEPTION_MESSAGE = 'Unique violation';

    private array $data;
    private ModeImportEnum $mode;
    private RentalRepositoryInterface $rentalRepository;

    public function __construct(
        array $data,
        string $mode,
    ) {
        $this->data = $data;
        $this->mode = ModeImportEnum::tryFrom($mode);
        // TODO: вынести в di
        $this->rentalRepository = new RentalRepository();
    }

    public function handle(): void
    {
        $rawData = $this->data;

        // TODO: factory + strategy
        if ($this->mode->isAdd()) {
            $this->recursionInsert($rawData);
        } else {
            // upsert method orm;
        }
    }

    private function filterByUniqueField(array $arr, array $arrUnique, string $field): array
    {
        foreach ($arr as $row) {
            if (in_array($row[$field], $arrUnique)) {
                $key = array_search($row[$field], $arrUnique);
                $data[$key] = $row;
            }
        }

        return $data ?? [];
    }

    private function makeUniqueValuesFromDB(array $arr, array $fields): array
    {
        $uniqueFieldValues = array_map(fn ($field) => array_column($arr, $field), $fields);

        $duplicateFieldValues = $this->rentalRepository->findDuplicateValues($uniqueFieldValues);

        return array_diff($uniqueFieldValues, $duplicateFieldValues);
    }

    private function recursionInsert(array $data): void
    {
        try {
            $this->rentalRepository->insert($data);
        } catch (QueryException $e) {
            $message = $e->getMessage();
            if (str_contains($message, self::EXCEPTION_MESSAGE)) {
                for ($i = 0; $i < count($data); $i++) {
                    preg_match(
                        '/(\(cars_id[\w,\s]*\))(=)((\()([\w\d\-:+,\s]*)\))/',
                        $message,
                        $matches,
                        PREG_OFFSET_CAPTURE
                    );
                    $duplicatedValues = array_pop($matches)[0];
                    $duplicatedValues = explode(', ', $duplicatedValues);
                    dd($data[$i]['rental_end'] == $duplicatedValues[2]);
                    if ($data[$i]['cars_id'] == $duplicatedValues[0]
                        && $data[$i]['rental_start'] == $duplicatedValues[1]
                        && $data[$i]['rental_end'] == $duplicatedValues[2]) {
                        dd(true);
                        unset($data[$i]);
                    }
                }
                $this->recursionInsert($data);
            }
        }
    }
}
