<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

interface DomainRulesInterface
{
    public static function rules(): array;
}
