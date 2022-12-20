<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum;

enum DayEnum: int
{
    case FOUR_DAY = 4;
    case NINE_DAY = 9;
    case SEVENTEEN_DAY = 17;
    case THIRTY_DAY = 30;

    private function getBinaryInt(): int
    {
        return match($this) {
            DayEnum::THIRTY_DAY => 1 << 3,
            DayEnum::SEVENTEEN_DAY => 1 << 2,
            DayEnum::NINE_DAY => 1 << 1,
            DayEnum::FOUR_DAY => 1 << 0,
        };
    }

    // TODO: заменить сравнение на побитовые операции
    public static function convertToBinary(int $value): int {
        $int = 0;
        foreach (DayEnum::cases() as $case) {
            $int |= $case->getBinaryInt();
            if ($value < $case->value) {
                break;
            }
        }

        return $int;
//        return match(true) {
//            $interval > DayEnum::THIRTY_DAY->value => 1 << DayEnum::THIRTY_DAY->getBinaryInt(),
//            $interval > DayEnum::SEVENTEEN_DAY->value => 1 << DayEnum::SEVENTEEN_DAY->getBinaryInt(),
//            $interval > DayEnum::NINE_DAY->value => 1 << DayEnum::NINE_DAY->getBinaryInt(),
//            $interval > DayEnum::FOUR_DAY->value => 1 << DayEnum::FOUR_DAY->getBinaryInt(),
//        };
    }

    public static function convertToArray(int $binaryInt): array {
        $result = [];
        foreach (DayEnum::cases() as $case) {
            if ($binaryInt & $case->getBinaryInt()) {
                $result[] = $case->value;
            }
        }
        return $result;
//        return match(true) {
//            $binaryInt == 1 >> DayEnum::THIRTY_DAY->getBinaryInt() => [DayEnum::FOUR_DAY, DayEnum::NINE_DAY, DayEnum::SEVENTEEN_DAY, DayEnum::THIRTY_DAY],
//            $binaryInt == 1 >> DayEnum::SEVENTEEN_DAY->getBinaryInt() => [DayEnum::FOUR_DAY, DayEnum::NINE_DAY, DayEnum::SEVENTEEN_DAY],
//            $binaryInt == 1 >> DayEnum::NINE_DAY->getBinaryInt() => [DayEnum::FOUR_DAY, DayEnum::NINE_DAY],
//            $binaryInt == 1 >> DayEnum::FOUR_DAY->getBinaryInt() => [DayEnum::FOUR_DAY],
//            default => [],
//        };
    }

    public static function values(): array
    {
        return array_map(fn (DayEnum $case) => $case->value, DayEnum::cases());
    }
}
