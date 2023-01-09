<?php

declare(strict_types=1);

namespace App\Http\Controllers\HealthCheck;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

final class HealthCheckController extends Controller
{
    #[OA\Get(
        path: '/healthcheck',
        operationId: 'index',
        description: 'Check connection to api',
        tags: ['HealthCheck'],
        responses: [
            new OA\Response(response: 200, description: 'Successful work of server api'),
        ],
    )]
    public function index()
    {
        return 'OK';
    }
}
