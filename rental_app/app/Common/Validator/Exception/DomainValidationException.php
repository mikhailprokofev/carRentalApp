<?php

declare(strict_types=1);

namespace App\Common\Validator\Exception;

use Exception;
use Throwable;

final class DomainValidationException extends Exception
{
    private const VALIDATION_MESSAGE = 'Incorrect data';

    public function __construct(string $field, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf($message, $field);
        parent::__construct($message ?? self::VALIDATION_MESSAGE, $code, $previous);
    }
}
