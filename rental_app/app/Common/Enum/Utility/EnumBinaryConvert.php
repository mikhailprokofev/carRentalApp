<?php

declare(strict_types=1);

namespace App\Common\Enum\Utility;

final class EnumBinaryConvert
{
    // TODO: заменить сравнение на побитовые операции
    public static function convertToBinary(string $class, int $value): int
    {
        $int = 0;

        foreach ($class::cases() as $case) {
            $int |= $case->getBinaryInt();
            if ($value < $case->value) {
                break;
            }
        }

        return $int;
    }

    public static function convertToArray(string $class, int $binaryInt): array
    {
        $result = [];

        foreach ($class::cases() as $case) {
            if ($binaryInt & $case->getBinaryInt()) {
                $result[] = $case->value;
            }
        }

        return $result;
    }
}
