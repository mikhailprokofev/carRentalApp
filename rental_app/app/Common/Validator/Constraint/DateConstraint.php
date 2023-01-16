<?php

declare(strict_types=1);

namespace App\Common\Validator\Constraint;

final class DateConstraint implements ConstraintInterface
{
    private static array $formats = [
        '/(\d{2})(%s)(\d{2})(%s)(\d{4})$/',
        '/(\d{4})(%s)(\d{2})(%s)(\d{2})$/',
    ];

    public static function validate(mixed $value, array $params = []): bool
    {
        $delimiter = $params['delimiter'] ?? '-';

        $matches = [];

        foreach (static::$formats as $format) {
            preg_match(sprintf($format, $delimiter, $delimiter), $value, $matches, PREG_OFFSET_CAPTURE);
            if (count($matches)) {
                break;
            }
        }

        if (!count($matches)) {
            return false;
        }

        $year = max($matches);
        $day = array_search($year, $matches) == 5 ? $matches[1] : $matches[3];
        $month = $matches[3];

        return checkdate((int) $month[0], (int) $day[0], (int) $year[0]);
    }
}
