<?php

declare(strict_types=1);

namespace App\Http\Controllers\FileSystem;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileSystem\ImportRentalRequest;
use App\Module\File\Handler\ImportRental\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class ImportRentalsController extends Controller
{
    public function __construct(
        private Handler $handler,
    ) {}

    public function __invoke(ImportRentalRequest $request): JsonResponse
    {
        $fileName = 'rental' . date("YmdHis") . '.csv';

        $request->validate($request->rules());

        $file = $request->file('file');
        $modeImport = $request->get('mode');

        if ($file) {
            try {
                $file->storeAs('/', $fileName);
                $this->handler->handle($fileName, $modeImport);

                return $this->successOutput();
            } catch (\Exception $e) {
                Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            }
        }

        return $this->failedOutput();
    }

    private function successOutput(): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData([
                'message' => 'Import in process...',
            ]);
    }

    private function failedOutput(): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode(404)
            ->setData([
                'message' => 'Error during import',
            ]);
    }
}
