<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ReportLoadCarsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'number_plate' => $this -> number_plate,
            'color' => $this -> color,
            'type' => $this -> type,
            'description' => $this -> description,
            'base_salary' => $this -> base_salary,
            'model' => $this -> model,
            'rental' => RentalResource::collection($this -> whenLoaded('rentals')),
            'created_at' => $this -> created_at,
            'updated_at' => $this -> updated_at,
        ];
    }
}
