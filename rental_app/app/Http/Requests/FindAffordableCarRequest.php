<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Common\Rules\WorkDayRule;
use Illuminate\Foundation\Http\FormRequest;

final class FindAffordableCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_at' => [
                'required',
                'date',
                new WorkDayRule(),
            ],
            'end_at' => [
                'required',
                'date',
                'after:start_at',
                new WorkDayRule(),
            ],
            'car_id' => 'nullable|string|uuid',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Необходимо заполнить поле :attribute.',
            'after' => ':attribute должна быть после :start_at.',
        ];
    }
}
