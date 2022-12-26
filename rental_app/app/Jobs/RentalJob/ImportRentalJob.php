<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Module\Import\Enum\ModeImportEnum;
use App\Module\Import\Factory\ImportStrategyFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ImportRentalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;
    private ModeImportEnum $mode;
    private ImportStrategyFactory $factory;

    public function __construct(
        array $data,
        string $mode,
    ) {
        $this->data = $data;
        $this->mode = ModeImportEnum::tryFrom($mode);
        // TODO: вынести в di
        $this->factory = new ImportStrategyFactory();
    }

    public function handle(): void
    {
        $importStrategy = $this->factory->make($this->mode);
        $importStrategy->import($this->data);
    }
}
