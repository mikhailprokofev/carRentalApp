<?php

declare(strict_types=1);

namespace App\Http\Controllers\HealthCheck;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

final class HealthCheckController extends Controller
{
    /**
     * @OA\Get(
     *      path="/healthcheck",
     *      tags={"HealthCheck"},
     *      operationId="index",
     *      description="Check connection to api",
     *      @OA\Response(
     *          response=200,
     *          description="Successful work of server api",
     *       ),
     * )
     */
    public function index()
    {
        return 'OK';
    }
}
