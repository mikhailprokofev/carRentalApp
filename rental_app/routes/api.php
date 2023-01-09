<?php

declare(strict_types=1);

use App\Http\Controllers\CarController;
use App\Http\Controllers\FileSystem\ExportController;
use App\Http\Controllers\FileSystem\ImportCarsController;
use App\Http\Controllers\FileSystem\ImportRentalsController;
use App\Http\Controllers\FindAffordableCarController;
use App\Http\Controllers\HealthCheck\HealthCheckController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReportLoadCarsController;
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
    'prefix' => 'export',
], function ($router) {
    Route::post('/cars', [ExportController::class, 'exportCars'])->name('export-name');
});

Route::group([
    'prefix' => 'import',
], function ($router) {
    Route::post('/cars', ImportCarsController::class)->name('import-cars');
    Route::post('/rentals', ImportRentalsController::class)->name('import-rentals');
});

Route::group([
    'prefix' => 'car',
], function ($router) {
    Route::post('/find/affordable', FindAffordableCarController::class)->name('car-find-affordable');
});

Route::group([
    'prefix' => 'report',
], function ($router) {
    Route::get('/load/cars/{year}/{month}', ReportLoadCarsController::class)->name('report-load-cars');
});

Route::resource('cars', CarController::class, ['only' => [
    'index', 'store', 'show', 'update', 'destroy',
]]);
Route::resource('rentals', RentalController::class, ['only' => [
    'index', 'store', 'show', 'update', 'destroy',
]]);
