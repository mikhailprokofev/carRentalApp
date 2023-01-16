<?php

declare(strict_types=1);

namespace App\Common\Validator\Rule;

use App\Common\Validator\DomainValidator;
use App\Common\Validator\Exception\DomainValidationException;

final class RentalDomainRule extends AbstractRule
{
    public function __construct(
        private DomainValidator $validator,
    ) {}

    protected function rules(): array
    {
        return [
            'rentalStart' => 'date', // |
            'rentalEnd' => 'date',
        ];
    }

    protected function messages(): array
    {
        $messages = [
//            'date' => 'exp',
        ];

        return array_merge(parent::messages(), $messages);
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
        $this->validator->validate($data, $this->rules(), $this->messages());
    }
}
