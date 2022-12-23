<?php

use App\Http\Controllers\FileSystem\ExportController;
use App\Http\Controllers\FileSystem\ImportCarsController;
use App\Http\Controllers\FileSystem\ImportRentalsController;
use App\Http\Controllers\HealthCheck\HealthCheckController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/healthcheck', [HealthCheckController::class, 'index']);

Route::group([
    'prefix' => 'export'
], function ($router) {
    Route::post('/cars', [ExportController::class, 'exportCars'])->name('export-name');
});

Route::group([
    'prefix' => 'import'
], function ($router) {
    Route::post('/cars', [ImportCarsController::class, 'import'])->name('import-cars');
    Route::post('/rentals', [ImportRentalsController::class, 'import'])->name('import-rentals');
});
