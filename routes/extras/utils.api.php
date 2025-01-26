<?php
use Illuminate\Support\Facades\Route;

Route::prefix('v1/utils')->group(function () {
    Route::get('{target}', \App\Http\Controllers\Api\Utils\GeneralController::class);
});
