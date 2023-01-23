<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Module\Rate\Service\RateCalculatingService;
use DateTimeImmutable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Container\Container;

final class RentalResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // TODO: How to fix that without Container?
        $rateService = Container::getInstance()->make(RateCalculatingService::class);

        $rate = $rateService->calculate(
            date_diff(
                new DateTimeImmutable($this->rental_end),
                new DateTimeImmutable($this->rental_start),
            )->days + 1,
            $this->start_salary,
        );

        return [
            'id' => $this->id,
            'start_salary' => number_format((float) round($this->start_salary / 100, 2), 2, '.', ''),
            'rental_start' => $this->rental_start,
            'rental_end' => $this->rental_end,
            'rate' => number_format((float) round($rate / 100, 2), 2, '.', ''),
            'car' => new CarResource($this->whenLoaded('car')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
