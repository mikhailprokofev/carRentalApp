<?php

namespace App\Jobs\RentalJob;

use App\Repository\CustomCustomCarRepository;
use App\Repository\CustomCarRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCarsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    private CustomCarRepositoryInterface $carRepository;

    public function __construct(
        array $data,
    ) {
        $this->data = $data;
        // TODO: вынести в di
        $this->carRepository = new CustomCustomCarRepository();
    }

    public function handle(): void
    {
        $rawData = $this->data;

        $uniqueFieldValues = $this->makeUniqueValuesFromDB($rawData, 'number_plate');

        $data = $this->filterByUniqueField($rawData, $uniqueFieldValues, 'number_plate');

        $this->carRepository->insert($data);
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

    private function makeUniqueValuesFromDB(array $arr, string $field): array
    {
        $uniqueFieldValues = array_column($arr, $field);

        $duplicateFieldValues = $this->carRepository->findDuplicateValues($uniqueFieldValues);

        return array_diff($uniqueFieldValues, $duplicateFieldValues);
    }
}
