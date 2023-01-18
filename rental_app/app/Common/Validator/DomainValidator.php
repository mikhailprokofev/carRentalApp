<?php

declare(strict_types=1);

namespace App\Common\Validator;

use App\Common\Validator\Exception\DomainValidationException;
use App\Common\Validator\Exception\ExistRuleException;
use App\Common\Validator\Constraint\DateConstraint;
use TypeError;

final class DomainValidator
{
    protected function listConstraints(): array
    {
        return [
            'date' => DateConstraint::class,
        ];
    }

    /**
     * @param array $data
     * @param array $constraint
     * @param array $messages
     * @return void
     * @throws DomainValidationException
     */
    public function validate(array $data, array $rules, array $messages): void
    {
        $constrains = $this->listConstraints();

        array_map(function ($field, $rule) use ($data, $messages, $constrains) {
//            dd($constraint);
            if (isset($constrains[$rule])) {
                try {
                    $isValidate = $constrains[$rule]::validate($data[$field]);

                    if (!$isValidate) {
                        throw new DomainValidationException($field, $messages[$rule]);
                    }
                } catch (TypeError $e) {
                    throw new DomainValidationException($field, $messages[$rule]);
                }
            } else {
                throw new ExistRuleException();
            }

        }, array_keys($rules), array_values($rules));
    }

    protected function messages(): array
    {
        return [
            'date' => 'Incorrect date: %s',
        ];
    }
}
