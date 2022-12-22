<?php

namespace App\Jobs\RentalJob;

use App\Module\File\Entity\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportCarsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private array $data,
    ) {}

    public function handle(): void
    {
        // TODO: запрос на проверку дублирования
        // TODO: создать репозиторий

        $data = array_map(fn (Car $car) => $car->toArray(), $this->data);
        DB::table('cars')->insert($data);
    }
}
