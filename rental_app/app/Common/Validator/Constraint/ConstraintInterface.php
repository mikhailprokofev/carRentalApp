<?php

declare(strict_types=1);

namespace App\Common\Validator\Constraint;

interface ConstraintInterface
{
    public static function validate(mixed $value, array $params = []): bool;
}
