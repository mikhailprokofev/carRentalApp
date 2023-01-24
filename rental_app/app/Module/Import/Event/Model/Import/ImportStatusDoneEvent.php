<?php

declare(strict_types=1);

namespace App\Module\Import\Event\Model\Import;

use App\Models\ImportStatus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ImportStatusDoneEvent
{
    use Dispatchable, SerializesModels;
//    use InteractsWithQueue;

//    /**
//     * Имя очереди, в которую должно быть отправлено задание.
//     *
//     * @var string|null
//     */
//    public $queue = 'listeners';

    public function __construct(
        private ImportStatus $importStatus,
    ) {}

    public function getImportStatus(): ImportStatus
    {
        return $this->importStatus;
    }
}
