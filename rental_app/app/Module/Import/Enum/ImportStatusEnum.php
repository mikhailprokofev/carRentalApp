<?php

declare(strict_types=1);

namespace App\Module\Import\Enum;

enum ImportStatusEnum: string
{
    case BEGIN = 'rewrite';
    case INPROGRESS = 'in_progress';
    case DONE = 'done';
    case ERROR = 'error';

    public static function getAll(): array
    {
        return array_map(fn ($status) => $status->value, self::cases());
    }
}
