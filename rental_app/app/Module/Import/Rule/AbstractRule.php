<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

abstract class AbstractRule
{
    abstract protected function rules(): array;

    protected function messages(): array
    {
        return [
            'date' => 'Incorrect date: %s',
        ];
    }
}
