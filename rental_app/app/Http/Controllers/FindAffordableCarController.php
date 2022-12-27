<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FindAffordableCarRequest;
use App\Module\Car\Handler\FindAffordableCar\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

final class FindAffordableCarController extends Controller
{
    public function __construct(
        private Handler $handler,
    ) {}

    public function __invoke(FindAffordableCarRequest $request): JsonResponse
    {
        // TODO: message из валидации не выводится
//        $test = new FindAffordableCarRequest();
//        try {
//            $this->validate($request, $test->rules(), $test->messages());
//            $test->validated();
//        } catch (ValidationException $e) {
//            dd($e->getMessage());
//        }

        $input = $request->validated();

        try {
            $this->handler->handle($input);

            return $this->successOutput();
        } catch (\Exception $e) {
            Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return $this->failedOutput();
    }

    private function successOutput(): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData([
                'message' => 'Found affordable car', // TODO: отдать данные машины(-)
                // items
            ]);
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
