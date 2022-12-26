<?php

declare(strict_types=1);

namespace App\Http\Controllers\FileSystem;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileSystem\ImportCarRequest;
use App\Module\File\Handler\ImportCar\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class ImportCarsController extends Controller
{
    public function __construct(
        private Handler $handler,
    ) {}

    public function import(ImportCarRequest $request): JsonResponse
    {
        $request->validate($request->rules());

        if ($file = $request->file('file')) {
            try {
                $file->storeAs('/', $fileName =  'car' . date("YmdHis") . '.csv');
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
