<?php

declare(strict_types=1);

namespace App\Module\Rate\Enum;

enum RuleFieldTypeEnum: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case UUID = 'uuid';
    case DATE = 'date';

    public static function isEqual(string $value): string
    {
        $filter = array_filter(
            RuleFieldTypeEnum::cases(),
            fn (RuleFieldTypeEnum $case) => str_contains($value, $case->value),
        );

        return ($test = array_pop($filter)) ?
            $test->value :
            RuleFieldTypeEnum::STRING->value;
    }
}
