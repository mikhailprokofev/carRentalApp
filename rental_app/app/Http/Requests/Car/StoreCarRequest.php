<?php

declare(strict_types=1);

namespace App\Http\Requests\Car;

use App\Module\Car\Enum\Type;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Model;
use App\Module\Car\Enum\Control;
use App\Module\Car\Enum\Country;
use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\Transmission;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

final class StoreCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'number_plate'      => 'required|string|unique:cars|number_plate',
            'country'           => ['required','string', new Enum(Country::class)],
            'brand'             => ['required','string', new Enum(Brand::class)],
            'model'             => ['required','string', new Enum(Model::class)],
            'color'             => ['required','string', new Enum(Color::class)],
            'manufacture_date'  => 'bail|required|integer|min:1900',
            'mileage'           => 'bail|required|integer|min:0',
            'drive'             => ['required','string', new Enum(Drive::class)],
            'control'           => ['required','string', new Enum(Control::class)],
            'body_type'         => ['required','string', new Enum(BodyType::class)],
            'transmission'      => ['required','string', new Enum(Transmission::class)],
            'insurance'         => ['required','string', new Enum(Insurance::class)],
            'class'             => ['required','string', new Enum(Type::class)],
            'description'       => 'nullable|string',
            'base_salary'       => 'bail|required|integer|min:1000|max:5000',
        ];
    }

    protected function prepareForValidation()
    {
        if (empty($this->country)){
            $this->merge([
                'country' => Country::getByBrand($this->brand),
            ]);
        }
    }
}
