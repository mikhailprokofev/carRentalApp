<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Common\Controller\Traits\OutputTrait;
use App\Http\Requests\ReportLoadCarsRequest;
use App\Module\Report\Load\Cars\Handler;
use App\Module\Report\Load\Cars\Input;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class ReportLoadCarsController extends Controller
{
    use OutputTrait;

    public function __construct(
        private Handler $handler,
    ) {}

    public function __invoke(ReportLoadCarsRequest $request, string $year, string $month): JsonResponse
    {
        $request->merge(['year' => $year, 'month' => $month]);
        $request->validate($request->rules());

        try {
            $result = $this->handler->handle(Input::make($year, $month));

            return $this->successOutput($result);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return $this->failedOutput($this->makeFailedOutputMessage($e));
    }
}
