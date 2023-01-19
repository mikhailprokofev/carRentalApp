<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CarResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'number_plate'      => $this->number_plate,
            'country'           => $this->country,
            'brand'             => $this->brand,
            'model'             => $this->model,
            'color'             => $this->color,
            'manufacture_date'  => $this->manufacture_date,
            'mileage'           => $this->mileage,
            'drive'             => $this->drive . ' wheel drive',
            'control'           => $this->control . '-hand drive car',
            'body_type'         => $this->body_type,
            'transmission'      => $this->transmission,
            'insurance'         => $this->insurance,
            'class'             => $this->class,
            'description'       => $this->description,
            'base_salary'       => number_format(
                                    round($this->base_salary / 100, 2), 2, '.', ''),
            'countCrashes'      => $this->count_crashes,
            'rental'            => RentalResource::collection($this->whenLoaded('rentals')),
            'operatingTime'     => $this->currentOperatingTimeString($this->created_at),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }

    private function currentOperatingTimeString(string|null $start): string
    {

        if (is_null($start))
            return 'new car (dont used)';

        $period = date_diff(
            new \DateTime($start),
            new \DateTime('now')
        );

        $result = $period->y > 0 ? $period->y . (($period->y == 1) ? ' year ' : ' years ') : '';
        $result .= $period->m > 0 ? $period->m . (($period->m == 1) ? ' mounth ' : ' mounths ') : '';

        $result .= ($result == '') ? '< 1 mounths' : '';

        return $result; 
    }
}
