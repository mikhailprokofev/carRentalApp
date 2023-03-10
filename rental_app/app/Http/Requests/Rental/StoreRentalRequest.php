<?php

declare(strict_types=1);

namespace App\Http\Requests\Rental;

use App\Repository\CustomCarRepository as CCR;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Uuid;
use DateTime;

final class StoreRentalRequest extends FormRequest
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
            'rental_start' => 'bail|required|date|after_or_equal:yesterday|workday',
            'rental_end' => 'bail|required|date|after_or_equal:rental_start|workday',
            'car_id' => 'required|uuid|string|exists:cars,id',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $startAt = $validator->validated()['rental_start'];
            $endAt = $validator->validated()['rental_end'];

            $this->addIntervalValidation($validator, $startAt, $endAt);
        });

        $validator->after(function (Validator $validator) {
            $car_id = $validator->validated()['car_id'];
            $startAt = $validator->validated()['rental_start'];
            $endAt = $validator->validated()['rental_end'];

            $sssr = new CCR();

            $result = $sssr->findAvailableCarById(Uuid::fromString($car_id), $startAt, $endAt);
            $car = $result->map(fn ($car) => $car->id)->toArray();

            if (empty($car)) {
                $validator->errors()->add(
                    'affordable',
                    'There are already rentals for the selected date range',
                );
            }
        });
    }

    private function addIntervalValidation(
        Validator $validator,
        string $startAt,
        string $endAt,
        int $interval = 30,
    ): void {
        if ($this->assertRentalInterval($startAt, $endAt, $interval)) {
            $validator->errors()->add(
                'interval',
                "The interval between the start and end dates must not be more than $interval days",
            );
        }
    }

    private function assertRentalInterval(string $startAt, string $endAt, int $interval): bool
    {
        return date_diff(new DateTime($endAt), new DateTime($startAt))->days > $interval;
    }
}
