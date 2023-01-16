<?php

declare(strict_types=1);

namespace App\Common\Validator;

use App\Common\Exception\DomainValidationException;

final class DomainValidator
{
    /**
     * @param array $data
     * @param array $constraint
     * @param array $messages
     * @return void
     * @throws DomainValidationException
     */
    public function validate(array $data, array $constraint, array $messages): void
    {
        array_map(function ($field, $constraint) use ($data, $messages) {
            $isValidate = $constraint($data[$field]);

            if (!$isValidate) {
                throw new DomainValidationException($messages[$field]);
            }
        }, array_keys($constraint), array_values($constraint));
    }
}
