<?php

declare(strict_types=1);

namespace App\Http\Requests\FileSystem;

use Illuminate\Foundation\Http\FormRequest;

final class ImportCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|mimes:csv|max:2048',
        ];
    }
}
