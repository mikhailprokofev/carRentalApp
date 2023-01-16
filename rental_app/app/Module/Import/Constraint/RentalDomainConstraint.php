<?php

declare(strict_types=1);

namespace App\Module\Import\Constraint;

use App\Common\Exception\DomainValidationException;
use App\Common\Validator\DomainValidator;
use Closure;

final class RentalDomainConstraint
{
    public function __construct(
        private DomainValidator $validator,
    ) {}

    public function constraints(): array
    {
        return [
            'field' => $this->validateField(),
        ];
    }

    private function validateField(): Closure
    {
        return fn ($value) => !is_null($value);
    }

    /**
     * Валидация данных
     *
     * @param array $data
     * @return void
     * @throws DomainValidationException
     */
    public function validate(array $data): void
    {
        $this->validator->validate($data, $this->constraints(), $this->messages());
    }

    private function messages(): array
    {
        return [
            'field' => 'fd',
        ];
    }
}
