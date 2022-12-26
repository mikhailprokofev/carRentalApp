<?php

declare(strict_types=1);

namespace App\Module\Import\Enum;

enum ModeImportEnum: string
{
    case REWRITE = 'rewrite';
    case ADD = 'add';

    public function isAdd(): bool
    {
        return $this->value === ModeImportEnum::ADD->value;
    }
}
