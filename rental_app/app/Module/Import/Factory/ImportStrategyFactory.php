<?php

declare(strict_types=1);

namespace App\Module\Import\Factory;

use App\Module\Import\Enum\ModeImportEnum;
use App\Module\Import\Strategy\InsertRental\AddStrategy;
use App\Module\Import\Strategy\InsertRental\InsertStrategyInterface;
use App\Module\Import\Strategy\InsertRental\RewriteStrategy;

final class ImportStrategyFactory
{
    public function make(ModeImportEnum $mode): InsertStrategyInterface
    {
        if ($mode->isRewrite()) {
            return new RewriteStrategy();
        }

        return new AddStrategy();
    }
}
