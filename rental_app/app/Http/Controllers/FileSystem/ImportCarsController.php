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

    public function __invoke(ImportCarRequest $request): JsonResponse
    {
        $fileName =  'car' . date("YmdHis") . '.csv';

        $request->validate($request->rules());

        $file = $request->file('file');

        if ($file) {
            try {
                $file->storeAs('/', $fileName);
                $this->handler->handle($fileName);

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
