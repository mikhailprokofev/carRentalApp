<?php

namespace App\Module\Rate\Enum;
enum RuleFieldTypeEnum: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case UUID = 'uuid';
    case DATE = 'date';

    public static function isEqual(string $value)
    {
        $filter = array_filter(
            RuleFieldTypeEnum::cases(),
            fn (RuleFieldTypeEnum $case) => str_contains($value, $case->value), 
        );

        // echo json_encode($filter);

        return ($test = array_pop($filter)) ? 
            $test->value : 
            RuleFieldTypeEnum::STRING->value;
    }
}