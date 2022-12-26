<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Module\Import\Enum\ModeImportEnum;
use App\Module\Import\Factory\ImportStrategyFactory;
use App\Module\Rate\Repository\RentalRepository;
use App\Module\Rate\Repository\RentalRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class ImportRentalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const EXCEPTION_MESSAGE = 'Unique violation';

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
//        if ($this->mode->isRewrite()) {
//            // TODO: transaction truncate + recursionInsert
//            $this->rentalRepository->truncate();
//        }
//
//        // TODO: проверка на доступность автомобиля
//        $this->recursionInsert($this->data);


        $importStrategy = $this->factory->make($this->mode);
        $importStrategy->import($this->data);
    }
}
