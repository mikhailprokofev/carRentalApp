<?php

declare(strict_types=1);

namespace App\Http\Requests;

use DateTime;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

final class CheckAvailableCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_at' => 'bail|required|date|after:today|workday',
            'end_at' => 'bail|required|date|after:start_at|workday',
            'number_plate' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
//            'required' => 'Необходимо заполнить поле :attribute',
//            'after:start_at' => ':attribute должна быть позже даты начала аренды',
//            'after:today' => ':attribute должна быть не раньше, чем завтра',
        ];
    }

//    public function attributes(): array
//    {
//        return [
//            'start_at' => 'Дата начала аренды',
//            'end_at' => 'Дата окончания аренды',
//        ];
//    }

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
