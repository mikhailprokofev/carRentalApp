<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRentalRequest extends FormRequest
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
            'start_salary'  => 'required|integer',
            'rental_start'  => 'required|date|after_or_equal:yesterday',
            'rental_end'    => 'required|date|after_or_equal:rental_start',
            'car_id'        => 'required|uuid|string'
        ];
    }
}
