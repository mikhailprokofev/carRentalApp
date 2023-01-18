<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

use Illuminate\Contracts\Validation\Validator;

final class CarDomainRules implements DomainRulesInterface
{
    public static function rules(): array
    {
        return [
            'number_plate' => 'required|string',
//            'color' => 'required|string',
//            'type' => 'required|string',
            'description' => 'nullable|string',
            'base_salary' => 'bail|required|integer|min:100000|max:500000',
            'model' => 'required|string',
        ];
    }

    public function afterValidate(Validator $validator): Validator
    {
        $validator->after(function (Validator $validator) {
            //
        });

        return $validator;
    }
}
