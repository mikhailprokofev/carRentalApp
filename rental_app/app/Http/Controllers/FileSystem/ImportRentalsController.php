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

    public function import(ImportRentalRequest $request): JsonResponse
    {
        $request->validate($request->rules());

        if ($file = $request->file('file')) {
            try {
                $file->storeAs('/', $fileName = date("YmdHis") . '.csv');
                $this->handler->handle($fileName);

                return (new JsonResponse())
                    ->setStatusCode(200)
                    ->setData([
                        'message' => 'Import in process...',
                    ]);
            } catch (\Exception $e) {
                Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            }
        }

        return (new JsonResponse())
            ->setStatusCode(404)
            ->setData([
                'message' => 'Error during import',
            ]);
    }
}
