<?php

declare(strict_types=1);

namespace App\Common\Validator\Exception;

use Exception;
use Throwable;

final class ExistRuleException extends Exception
{
    private const MESSAGE = 'There are not existing rule';

    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, $code, $previous);
    }
}
