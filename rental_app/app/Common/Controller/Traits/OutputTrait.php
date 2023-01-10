<?php

declare(strict_types=1);

namespace App\Common\Controller\Traits;

use DomainException;
use Exception;
use Illuminate\Http\JsonResponse;

trait OutputTrait
{
    protected function getDefaultErrorMessage(): string
    {
        return 'Bad Request';
    }

    protected function successOutput(mixed $result, int $code = 200): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode($code)
            ->setData($result);
    }

    protected function failedOutput(string $message, int $code = 404): JsonResponse
    {
        return (new JsonResponse())
            ->setStatusCode($code)
            ->setData([
                'message' => $message,
            ]);
    }

    protected function makeFailedOutputMessage(Exception $e): string
    {
        return $e instanceof DomainException ? $e->getMessage() : $this->getDefaultErrorMessage();
    }
}
