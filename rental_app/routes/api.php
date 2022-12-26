<?php

use App\Http\Controllers\HealthCheck\HealthCheckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentalController;

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

Route::resource('cars', CarController::class, ['only' => [
    'index', 'store', 'show', 'update', 'destroy'
]]);
Route::resource('rentals', RentalController::class, ['only' => [
    'index', 'store', 'show', 'update', 'destroy'
]]);