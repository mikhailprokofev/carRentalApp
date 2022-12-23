<?php

declare(strict_types=1);

namespace App\Http\Requests\FileSystem;

use Illuminate\Foundation\Http\FormRequest;

final class ImportRentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|mimes:txt,csv',
        ];
    }
}
