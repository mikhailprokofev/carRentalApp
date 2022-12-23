<?php

namespace App\Jobs\RentalJob;

use App\Module\File\Entity\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportCarsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $data,
    ) {}

    public function handle(): void
    {
        $rawData = array_map(fn (Car $car) => $car->toArray(), $this->data);

        $uniqueFieldValues = $this->makeUniqueValuesFromDB($rawData, 'number_plate');

        $data = $this->filterByUniqueField($rawData, $uniqueFieldValues, 'number_plate');

        // TODO: вынести в репозиторий
        if (count($data)) {
            DB::table('cars')->insert($data);
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

    private function makeUniqueValuesFromDB(array $arr, string $field): array
    {
        $uniqueFieldValues = array_column($arr, $field);

        $duplicateFieldValues = $this->findDuplicateValues($uniqueFieldValues);

        return array_diff($uniqueFieldValues, $duplicateFieldValues);
    }

    // TODO: вынести в репозиторий
    private function findDuplicateValues(array $values): array
    {
        return DB::table('cars')
            ->select('number_plate')
            ->whereIn('number_plate', $values)
            ->get()
            ->map(fn ($car) => $car->number_plate)
            ->toArray();
    }
}
