<?php

declare(strict_types=1);

namespace App\Module\File\Enum;

enum ModeImportEnum: string
{
    case REWRITE = 'rewrite';
    case ADD = 'add';
}
