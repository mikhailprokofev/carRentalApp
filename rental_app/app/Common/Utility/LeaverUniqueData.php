<?php

declare(strict_types=1);

namespace App\Common\Utility;

use App\Repository\DuplicatedRepositoryInterface;

final class LeaverUniqueData
{
    /**
     * Если имеется несколько дублей, то при проходе массивов остается в результирующем массиве последний дубляж
     *
     * @param array $uniqueData
     * @param array $data
     * @param string $uniqueFieldName
     * @return array
     */
    public static function leaveUniqueValuesFromArrays(array $uniqueData, array $data, string $uniqueFieldName): array
    {
        foreach ($data as $row) {
            if (in_array($row[$uniqueFieldName], $uniqueData)) {
                $key = array_search($row[$uniqueFieldName], $uniqueData);
                $unique[$key] = $row;
            }
        }

        return $unique ?? [];
    }

    public static function leaveUniqueValuesByDB(
        DuplicatedRepositoryInterface $duplicatedRepository,
        array $data,
        string $uniqueFieldName,
    ): array {
        $fieldValues = array_column($data, $uniqueFieldName);
        $duplicatedFieldValues = $duplicatedRepository->findDuplicateValues($fieldValues);

        return array_diff($fieldValues, $duplicatedFieldValues);
    }
}
