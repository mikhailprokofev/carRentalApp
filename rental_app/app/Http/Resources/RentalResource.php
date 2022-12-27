<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CarResource;

class RentalResource extends JsonResource
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
            'id'            => $this->id,
            'start_salary'  => $this->start_salary,
            'rental_start'  => $this->rental_start,
            'rental_end'    => $this->rental_end,
            'car'           => new CarResource($this->whenLoaded('car')),
            'created_at'    => $this -> created_at,
            'updated_at'    => $this -> updated_at,
        ];
    }
}
