<?php

use App\Http\Controllers\Api\Admin\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/admin')->group(function () {
    Route::prefix('analytics')->group(function () {
        Route::get('', [AnalyticsController::class, 'index']);
        Route::get('users/{target}', [AnalyticsController::class, 'targetUsers']);
        Route::get('groups/{target}', [AnalyticsController::class, 'targetGroups']);

        Route::prefix('businesses')->group(function () {
            Route::get('', [\App\Http\Controllers\Api\Admin\BusinessesAnalyticsController::class, 'index']);
            Route::get('report', [\App\Http\Controllers\Api\Admin\BusinessesAnalyticsController::class, 'reports']);

        });

        Route::prefix('individuals')->group(function () {
            Route::get('', [\App\Http\Controllers\Api\Admin\IndividualsAnalyticsController::class, 'index']);
            Route::get('report', [\App\Http\Controllers\Api\Admin\IndividualsAnalyticsController::class, 'reports']);
        });
    });




});
