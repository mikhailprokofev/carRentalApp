<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     version="1.0",
 *     title="Car rental API",
 *     description="Car rental apllication based on microservice architecture",
 *     @OA\Contact(name="mikhail.prokofev@fojin.tech OR anastasia.shamsudinova@fojin.tech")
 * )
 * @OA\Server(
 *     url="http://localhost",
 *     description="API server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
