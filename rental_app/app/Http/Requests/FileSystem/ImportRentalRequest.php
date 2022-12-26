<?php

declare(strict_types=1);

namespace App\Http\Requests\FileSystem;

use App\Module\File\Enum\ModeImportEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'mode' => [new Enum(ModeImportEnum::class)], // TODO: подумать как в виде строки задать
        ];
    }
}
