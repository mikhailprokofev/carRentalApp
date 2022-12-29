<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RentalResource;

class CarResource extends JsonResource
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
            'id'            => $this -> id,
            'number_plate'  => $this -> number_plate,
            'color'         => $this -> color,
            'type'          => $this -> type,
            'description'   => $this -> description,
            'base_salary'   => number_format((float)round($this->base_salary / 100,2), 2, '.', ''),
            'model'         => $this -> model,
            'rental'        => RentalResource::collection($this -> whenLoaded('rentals')),
            'created_at'    => $this -> created_at,
            'updated_at'    => $this -> updated_at,
        ];
    }
}
