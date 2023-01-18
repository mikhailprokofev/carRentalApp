<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\Model;
use App\Module\Car\Enum\Transmission;
use App\Module\Car\Enum\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'number_plate' => 'required|string|unique:cars',
            'color' => ['required', new Enum(Color::class)],
            'type' => ['required', new Enum(Type::class)],
            'model' => ['required', new Enum(Model::class)],
            'brand' => ['required', new Enum(Brand::class)],
            'drive' => ['required', new Enum(Drive::class)],
            'body_type' => ['required', new Enum(BodyType::class)],
            'transmission' => ['required', new Enum(Transmission::class)],
            'insurance' => ['required', new Enum(Insurance::class)],
            'manufacture_date' => 'bail|required|date|before:today',
            'mileage' => 'bail|required|integer|min:0',
            'is_right_hand' => 'bail|boolean',
            'description' => 'nullable|string',
            'base_salary' => 'bail|required|integer|min:1000|max:5000',
        ];
    }
}
