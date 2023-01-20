<?php

declare(strict_types=1);

namespace App\Module\Import\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum ImportStatusEnum: string
{
    use EnumToArray;

    case BEGIN = 'begin';
    case INPROGRESS = 'in_progress';
    case DONE = 'done';
    case ERROR = 'error';
}
