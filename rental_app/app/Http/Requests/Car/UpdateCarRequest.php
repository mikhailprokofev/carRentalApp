<?php

declare(strict_types=1);

namespace App\Http\Requests\Car;

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
            'color' => ['string', new Enum(Color::class)],
            'class' => ['string', new Enum(Type::class)],
            'model' => ['string', new Enum(Model::class)],
            'brand' => ['string', new Enum(Brand::class)],
            'drive' => ['string', new Enum(Drive::class)],
            'body_type' => ['string', new Enum(BodyType::class)],
            'transmission' => ['string', new Enum(Transmission::class)],
            'insurance' => ['string', new Enum(Insurance::class)],
            'control' => ['string', new Enum(Control::class)],
            'manufacture_date' => 'bail|integer',
            'mileage' => 'bail|integer|min:0',
        ];
    }
}
