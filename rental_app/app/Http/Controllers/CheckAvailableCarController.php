<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Common\Controller\Traits\OutputTrait;
use App\Http\Requests\CheckAvailableCarRequest;
use App\Module\Car\Handler\CheckAvailable\Handler;
use App\Module\Car\Handler\CheckAvailable\Input;
use DomainException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class CheckAvailableCarController extends Controller
{
    use OutputTrait;

    public function __construct(
        private Handler $handler,
    ) {}

    public function __invoke(CheckAvailableCarRequest $request): JsonResponse
    {
        try {
            $result = $this->handler->handle(Input::make($request));
            return $this->successOutput($result);
        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return $this->failedOutput($this->makeFailedOutputMessage($e));
    }
}
