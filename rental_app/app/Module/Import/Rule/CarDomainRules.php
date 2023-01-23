<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Control;
use App\Module\Car\Enum\Country;
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
            'class' => ['required','string', new Enum(Type::class)],
            'model' => ['required', new Enum(Model::class)],
            'brand' => ['required', new Enum(Brand::class)],
            'base_salary' => 'bail|required|integer|min:100000|max:500000',
            'color' => ['required', new Enum(Color::class)],
            'drive' => ['required', new Enum(Drive::class)],
            'control' => ['required', new Enum(Control::class)],
            'body_type' => ['required', new Enum(BodyType::class)],
            'transmission' => ['required', new Enum(Transmission::class)],
            'insurance' => ['required', new Enum(Insurance::class)],
            'manufacture_date' => 'bail|required|integer|min:1950',
            'mileage' => 'bail|required|integer|min:0',
            'description' => 'nullable|string',
            'country' => ['required','string', new Enum(Country::class)],
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
