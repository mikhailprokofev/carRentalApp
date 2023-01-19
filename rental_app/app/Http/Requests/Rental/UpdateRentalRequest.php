<?php

declare(strict_types=1);

namespace App\Http\Requests\Rental;

use DateTime;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateRentalRequest extends FormRequest
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
            'rental_start' => 'bail|date|after_or_equal:yesterday|workday',
            'rental_end' => 'bail|date|after_or_equal:rental_start|workday',
            'car_id' => 'uuid|string|exists:cars,id',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $startAt = $validator->validated()['rental_start'] ?? null;
            $endAt = $validator->validated()['rental_end'] ?? null;
            if (! empty($startAt) && ! empty($endAt)) {
                $this->addIntervalValidation($validator, $startAt, $endAt);
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
