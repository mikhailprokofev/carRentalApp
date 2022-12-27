<?php

declare(strict_types=1);

namespace App\Common\Rules;

use Illuminate\Contracts\Validation\Rule;

final class WorkDayRule implements Rule
{
    public function __construct() {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, mixed $value): bool
    {
        $weekDay = date('w', strtotime($value));
        return ($weekDay != 0 && $weekDay != 6);
    }

    public function message(): string
    {
        return 'The :attribute must be work day';
    }
}
