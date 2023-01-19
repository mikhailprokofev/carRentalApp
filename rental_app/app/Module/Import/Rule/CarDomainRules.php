<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\Model;
use App\Module\Car\Enum\Transmission;
use App\Module\Car\Enum\Type;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Enum;

final class CarDomainRules implements DomainRulesInterface
{
    public static function rules(): array
    {
        return [
            'number_plate' => 'required|string|number_plate',
            'description' => 'nullable|string',
            'base_salary' => 'bail|required|integer|min:100000|max:500000',
            'color' => ['required', new Enum(Color::class)],
            'type' => ['required', new Enum(Type::class)],
            'model' => ['required', new Enum(Model::class)],
            'brand' => ['required', new Enum(Brand::class)],
            'drive' => ['required', new Enum(Drive::class)],
            'body_type' => ['required', new Enum(BodyType::class)],
            'transmission' => ['required', new Enum(Transmission::class)],
            'insurance' => ['required', new Enum(Insurance::class)],
            'control' => ['required', new Enum(Control::class)],
            'manufacture_date' => 'bail|required|integer',
            'mileage' => 'bail|required|integer|min:0',
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
