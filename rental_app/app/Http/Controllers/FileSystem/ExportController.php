<?php

declare(strict_types=1);

namespace App\Http\Controllers\FileSystem;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileSystem\ExportCarRequest;
use Illuminate\Http\Response;

final class ExportController extends Controller
{
    /**
     * @param ExportCarRequest $request
     * @return Response
     */
    public function exportCars(ExportCarRequest $request): Response
    {
    }
}
