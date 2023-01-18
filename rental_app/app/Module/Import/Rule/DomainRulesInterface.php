<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

use Illuminate\Contracts\Validation\Validator;

interface DomainRulesInterface
{
    public static function rules(): array;

    public function afterValidate(Validator $validator): Validator;
}
