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

final class UpdateCarRequest extends FormRequest
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
            'number_plate' => 'string|unique:cars|number_plate',
            'description' => 'string',
            'base_salary' => 'bail|integer|min:1000|max:5000',
            'color' => [new Enum(Color::class)],
            'type' => [new Enum(Type::class)],
            'model' => [new Enum(Model::class)],
            'brand' => [new Enum(Brand::class)],
            'drive' => [new Enum(Drive::class)],
            'body_type' => [new Enum(BodyType::class)],
            'transmission' => [new Enum(Transmission::class)],
            'insurance' => [new Enum(Insurance::class)],
            'manufacture_date' => 'bail|date|before:today',
            'mileage' => 'bail|integer|min:0',
            'is_right_hand' => 'bail|boolean',
        ];
    }
}
