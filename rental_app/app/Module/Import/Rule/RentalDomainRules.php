<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

use DateTime;
use Illuminate\Contracts\Validation\Validator;

final class RentalDomainRules
{
    // TODO: проверка на доступность автомобиля
    public static function rules(): array
    {
        return [
            'rental_start' => 'bail|required|date|after_or_equal:yesterday|workday',
            'rental_end' => 'bail|required|date|after_or_equal:rental_start|workday',
            'car_id' => 'required|exists:cars,id',
            'start_salary' => 'bail|required|integer|min:100000|max:500000'
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $startAt = $validator->validated()['start_at'];
            $endAt = $validator->validated()['end_at'];

            $this->addIntervalValidation($validator, $startAt, $endAt);
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
