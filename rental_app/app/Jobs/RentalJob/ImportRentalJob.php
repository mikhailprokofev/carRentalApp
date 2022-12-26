<?php

declare(strict_types=1);

namespace App\Jobs\RentalJob;

use App\Module\Import\Enum\ModeImportEnum;
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
    private RentalRepositoryInterface $rentalRepository;

    public function __construct(
        array $data,
        string $mode,
    ) {
        $this->data = $data;
        $this->mode = ModeImportEnum::tryFrom($mode);
        // TODO: вынести в di
        $this->rentalRepository = new RentalRepository();
    }

    public function handle(): void
    {
        if ($this->mode->isRewrite()) {
            // TODO: transaction truncate + recursionInsert
            $this->rentalRepository->truncate();
        }

        // TODO: проверка на доступность автомобиля
        $this->recursionInsert($this->data);
    }

    private function recursionInsert(array $data): void
    {
        try {
            $this->insertData($data);
        } catch (QueryException $e) {
            $message = $e->getMessage();
            if (str_contains($message, self::EXCEPTION_MESSAGE)) {
                foreach ($data as $key => $row) {
                    preg_match(
                        '/(\(cars_id[\w,\s]*\))(=)((\()([\w\d\-:+,\s]*)\))/',
                        $message,
                        $matches,
                        PREG_OFFSET_CAPTURE
                    );
                    $duplicatedValues = array_pop($matches)[0];
                    $duplicatedValues = explode(', ', $duplicatedValues);

                    if ($row['cars_id'] == $duplicatedValues[0]
                        && $row['rental_start'] == $duplicatedValues[1]
                        && $row['rental_end'] == $duplicatedValues[2]) {
                        // TODO: LOG INFO
                        unset($data[$key]);
                        break;
                    }
                }

                $this->recursionInsert($data);
            } else {
                Log::error($message);
            }
        }
    }

    private function insertData(array $data): void
    {
        if (count($data)) {
            $this->rentalRepository->insert($data);
        }
    }
}
