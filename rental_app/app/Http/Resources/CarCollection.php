<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\Rules\Enum;
use App\Module\Car\Enum\Transmission;
use App\Module\Car\Enum\Insurance;
use App\Module\Car\Enum\BodyType;
use App\Module\Car\Enum\Country;
use App\Module\Car\Enum\Control;
use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Color;
use App\Module\Car\Enum\Drive;
use App\Module\Car\Enum\Model;
use App\Module\Car\Enum\Type;

final class CarCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'avalible_filters' => [
                'country'  => Country::toArray(),
                'brand' => Brand::class::toArray(),
                'model' => Model::class::toArray(),
                'color' => Color::class::toArray(),
                'class' => Type::class::toArray(),
                'manufacture_date' => 'integer',
                'mileage' => 'integer',
                'drive' => Drive::class::toArray(),
                'control' => Control::class::toArray(),
                'body_type' => BodyType::class::toArray(),
                'transmission' => Transmission::class::toArray(),
                'insurance' => Insurance::class::toArray(),
            ],
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
