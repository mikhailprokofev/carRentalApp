<?php

declare(strict_types=1);

namespace App\Common\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

final class WorkDayRule implements Rule
{
    protected const RULE = 'workday';

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
        return ':attribute должен быть рабочим днем';
    }

    public static function handle(): string
    {
        return static::RULE;
    }


    public function validate(string $attribute, $value, $params, Validator $validator): bool
    {
        $handle = $this->handle();


        $validator->setCustomMessages([
            $handle => $this->message(),
        ]);

        return $this->passes($attribute, $value);
    }
}
