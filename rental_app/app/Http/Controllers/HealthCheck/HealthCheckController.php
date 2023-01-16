<?php

declare(strict_types=1);

namespace App\Http\Controllers\HealthCheck;

use App\Common\Validator\DomainValidator;
use App\Http\Controllers\Controller;
use App\Models\ImportStatus;
use App\Module\Import\Constraint\RentalDomainConstraint;
use App\Module\Import\Enum\ImportStatusEnum;
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
        $constraint = new RentalDomainConstraint(new DomainValidator());
        $constraint->validate(['field' => 123]);
//        $import = ImportStatus::create(['status' => ImportStatusEnum::DONE->value]);
//        dd($import);
        return 'OK';
    }
}
