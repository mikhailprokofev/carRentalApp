<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Module\Report\Load\Cars\Handler;
use App\Module\Report\Load\Cars\Input;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class ReportLoadCarsController extends Controller
{
    public function __construct(
        private Handler $handler,
    ) {}

    public function __invoke(string $year, string $month): JsonResponse
    {
        try {
            $result = $this->handler->handle(Input::make($year, $month));

            return $this->successOutput($result);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return $this->failedOutput();
    }

    private function successOutput(array $items): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData($items);
    }

    private function failedOutput(): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode(404)
            ->setData([
                'message' => 'Error during generate report',
            ]);
    }
}
