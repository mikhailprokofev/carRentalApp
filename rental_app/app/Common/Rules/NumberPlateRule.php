<?php

declare(strict_types=1);

namespace App\Common\Rules;

use App\Module\Car\Utility\NumberPlate;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

final class NumberPlateRule implements Rule
{
    private const RULE = 'number_plate';

    // TODO: кидать сюда утилу
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
        $utility = new NumberPlate();

        return $utility->isCorrect($value);
    }

    public function message(): string
    {
        return ':attribute must match the Russian law \'Р 50577-93\'';
    }

    public static function handle(): string
    {
        return self::RULE;
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
