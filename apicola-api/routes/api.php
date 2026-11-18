<?php

use App\Http\Controllers\Api\V1\SensorColmenaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('colmenas', SensorColmenaController::class);
});