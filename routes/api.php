<?php

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


foreach (File::allFiles(__DIR__ . '/extras') as $route_file) {
    require $route_file->getPathname();
}

Route::group(['prefix' => 'v1'], function () {
    
    Route::group(['middleware' => 'integration.app'], function () {
        Route::group(['prefix' => 'integration'], function () {
            Route::post('initiate', [\App\Http\Controllers\Api\Integration\AuthenticationsController::class, 'initiate']);
            Route::post('auth/generate-token', [\App\Http\Controllers\Api\Integration\AuthenticationsController::class, 'generateToken']);

        });
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', \App\Http\Controllers\Api\Auth\RegistrationsController::class);
        Route::post('login', [\App\Http\Controllers\Api\Auth\AuthenticationsController::class, 'login']);
        Route::post('google-verify', [\App\Http\Controllers\Api\Auth\AuthenticationsController::class, 'googleVerify']);
        Route::post('forgot-password', [\App\Http\Controllers\Api\Auth\AuthenticationsController::class, 'forgotPassword']);
        Route::post('reset-password', [\App\Http\Controllers\Api\Auth\AuthenticationsController::class, 'resetPassword']);
        Route::post('activate-account', [\App\Http\Controllers\Api\Auth\AuthenticationsController::class, 'activateAccount']);
    });

    Route::post('/calculator/demo', [\App\Http\Controllers\Api\CarbonEmissionCalculatorController::class, 'demo']);

    Route::group(['middleware' => 'auth:api'], function() {

        Route::group(['prefix' => 'auth/manage'], function () {
            Route::post('update/{type}', [\App\Http\Controllers\Api\Auth\UserManagementsController::class, 'update']);

        });
        Route::group(['prefix' => 'teams'], function (){
            Route::post('', [\App\Http\Controllers\Api\TeamManagementsController::class, 'create']);
            Route::put('{team}', [\App\Http\Controllers\Api\TeamManagementsController::class, 'update']);
            Route::get('', [\App\Http\Controllers\Api\TeamManagementsController::class, 'index']);
            Route::delete('{team}', [\App\Http\Controllers\Api\TeamManagementsController::class, 'destroy']);
            Route::get('{team}', [\App\Http\Controllers\Api\TeamManagementsController::class, 'show']);
        });

        Route::post('send-referral-email', [\App\Http\Controllers\Api\ReferralController::class, 'sendReferralEmail']);

        Route::group(['prefix' => 'calculator'], function() {
            Route::post('energy', [\App\Http\Controllers\Api\CarbonEmissionCalculatorController::class, 'calculateEnergyEmission']);
            Route::post('transportation', [\App\Http\Controllers\Api\CarbonEmissionCalculatorController::class, 'calculateTransportationEmission']);
            Route::post('lifestyle', [\App\Http\Controllers\Api\CarbonEmissionCalculatorController::class, 'calculateLifestyleEmission']);
            Route::post('save', [\App\Http\Controllers\Api\CarbonEmissionCalculatorController::class, 'saveCarbonEmissionData']);
            Route::get('histories', [\App\Http\Controllers\Api\CarbonEmissionCalculatorController::class, 'histories']);
            Route::post('download-histories', [\App\Http\Controllers\Api\CarbonEmissionCalculatorController::class, 'downloadHistories']);
        });

        Route::group(['prefix' => 'goal'], function () {
            Route::post('create', [\App\Http\Controllers\Api\GoalSettingsController::class, 'create']);
            Route::get('', [\App\Http\Controllers\Api\GoalSettingsController::class, 'index']);
        });

    });

});



