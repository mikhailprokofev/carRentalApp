<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ListAvailableCarRequest;
use App\Module\Car\Handler\ListAvailable\Handler;
use App\Module\Car\Handler\ListAvailable\Input;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class ListAvailableCarController extends Controller
{
    public function __construct(
        private Handler $handler,
    ) {}

    public function __invoke(ListAvailableCarRequest $request): JsonResponse
    {
        try {
            $result = $this->handler->handle(Input::make($request));

            return $this->successOutput($result);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return $this->failedOutput();
    }

    private function successOutput(array $result): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData($result);
    }

    private function failedOutput(): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode(404)
            ->setData([
                'message' => 'Not found affordable car',
            ]);
    }
}
