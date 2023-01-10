<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCarRequest extends FormRequest
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
            'number_plate' => 'string',
            'color' => 'string',
            'type' => 'string',
            'description' => 'string',
            'base_salary' => 'bail|integer|min:1000|max:5000',
            'model' => 'string',
        ];
    }
}
