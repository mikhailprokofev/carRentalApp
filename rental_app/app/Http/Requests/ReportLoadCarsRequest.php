<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ReportLoadCarsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [];
//        return [
//            'month' => 'required|integer|max:12|min:1',
//            'year' => 'required|integer|max:' . date('Y', time()) . '|min:1970',
//        ];
    }

    public function messages(): array
    {
        return [
            'max' => ':attribute не должен быть больше 12',
            'min' => ':attribute не должен быть меньше 1',
            'required' => 'Необходимо заполнить поле :attribute',
        ];
    }

    public function attributes(): array
    {
        return [
            'month' => 'Месяц',
            'year' => 'Год',
        ];
    }
}
