<?php

declare(strict_types=1);

namespace App\Http\Requests;

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
            'start_at' => 'required|date|after:yesterday|workday',
            'end_at' => 'required|date|after:start_at|workday',
            'car_id' => 'nullable|string|uuid',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Необходимо заполнить поле :attribute',
            'after:start_at' => ':attribute должна быть позже даты начала аренды',
//            'after:yesterday' => ':attribute должна быть позже',
        ];
    }

    public function attributes(): array
    {
        return [
            'start_at' => 'Дата начала аренды',
            'end_at' => 'Дата окончания аренды',
        ];
    }
}
