<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Common\Controller\Traits\OutputTrait;
use App\Http\Requests\CheckAvailableCarRequest;
use App\Http\Requests\ImportStatusViewRequest;
use App\Module\ImportStatus\Handler\View\Handler;
use App\Module\ImportStatus\Handler\View\Input;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class ImportStatusViewController extends Controller
{
    use OutputTrait;

    public function __construct(
        private Handler $handler,
    ) {}

    public function __invoke(ImportStatusViewRequest $request, string $filename): JsonResponse
    {
        $request->merge(['filename' => $filename]);
        $request->validate($request->rules());

        try {
            $result = $this->handler->handle(Input::make($request));
            return $this->successOutput($result);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return $this->failedOutput($this->makeFailedOutputMessage($e));
    }
}
