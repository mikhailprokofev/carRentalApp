<?php

declare(strict_types=1);

namespace App\Http\Requests\Car;

use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Country;
use App\Module\Car\Enum\Control;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\Model;
use App\Module\Car\Enum\Transmission;
use App\Module\Car\Enum\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class IndexCarRequest extends FormRequest
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
            'country'               => ['string', new Enum(Country::class)],
            'brand'                 => ['string', new Enum(Brand::class)],
            'model'                 => ['string', new Enum(Model::class)],
            'color'                 => ['string', new Enum(Color::class)],
            'manufacture_date'      => 'bail|integer',
            'min_manufacture_date'  => 'bail|integer',
            'max_manufacture_date'  => 'bail|integer',
            'mileage'               => 'bail|integer|min:0',
            'min_mileage'           => 'bail|integer|min:0',
            'max_mileage'           => 'bail|integer|min:0',
            'drive'                 => ['string', new Enum(Drive::class)],
            'control'               => ['string', new Enum(Control::class)],
            'body_type'             => ['string', new Enum(BodyType::class)],
            'transmission'          => ['string', new Enum(Transmission::class)],
            'insurance'             => ['string', new Enum(Insurance::class)],
            'class'                 => ['string', new Enum(Type::class)],
            'base_salary'           => 'bail|integer|min:1000',
            'min_base_salary'       => 'bail|integer|min:1000',
            'max_base_salary'       => 'bail|integer|min:5000',
        ];
    }
}
