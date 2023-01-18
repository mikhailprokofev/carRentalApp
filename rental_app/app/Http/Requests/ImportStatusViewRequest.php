<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Http\Request;

final class ImportStatusViewRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filename' => 'required|string',
        ];
    }
}
