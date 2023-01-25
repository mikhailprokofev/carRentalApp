<?php

declare(strict_types=1);

namespace App\Module\Import\Validator;

use App\Module\Import\Rule\DomainRulesInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorDomain;
use Illuminate\Validation\ValidationException;

final class DomainValidator
{
    private ValidatorDomain $validator;

    public function __construct(
        private DomainRulesInterface $domainRule,
    ) {}

    /**
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public function validate(array $data): void
    {
        $this->validator = Validator::make($data, $this->domainRule->rules());

        $this->afterValidate();

        $this->validator->validated();
    }

    /**
     * @return void
     * @throws ValidationException
     */
    private function afterValidate(): void
    {
        $this->validator = $this->domainRule->afterValidate($this->validator);

        if ($this->validator->fails()) {
            throw new ValidationException($this->validator);
        }
    }
}
