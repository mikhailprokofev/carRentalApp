<?php

declare(strict_types=1);

namespace App\Http\Requests\Car;

use App\Module\Car\Enum\Type;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Model;
use App\Module\Car\Enum\Control;
use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\Transmission;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

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
            'number_plate' => 'required|string|unique:cars|number_plate',
            'color' => ['required','string', new Enum(Color::class)],
            'class' => ['required','string', new Enum(Type::class)],
            'model' => ['required','string', new Enum(Model::class)],
            'brand' => ['required','string', new Enum(Brand::class)],
            'drive' => ['required','string', new Enum(Drive::class)],
            'body_type' => ['required','string', new Enum(BodyType::class)],
            'transmission' => ['required','string', new Enum(Transmission::class)],
            'insurance' => ['required','string', new Enum(Insurance::class)],
            'control' => ['required','string', new Enum(Control::class)],
            'manufacture_date' => 'bail|required|integer',
            'mileage' => 'bail|required|integer|min:0',
            'description' => 'nullable|string',
            'base_salary' => 'bail|required|integer|min:1000|max:5000',
        ];
    }
}
