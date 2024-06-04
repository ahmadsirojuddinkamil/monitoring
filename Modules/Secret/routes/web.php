<?php

use Illuminate\Support\Facades\Route;
use Modules\Secret\App\Http\Controllers\SecretController;

Route::middleware('auth_user')->controller(SecretController::class)->group(function () {
    Route::get('/secret-generator', 'viewSecretGenerator')->name('view.secret.generator');
    Route::post('/secret-generator', 'generateKey')->name('generate.key');

    Route::get('/get-data-response', 'getDatResponse');

    Route::get('/data-logging/register', 'pageRegister');
    Route::get('/data-logging/login', 'pageLogin');
    Route::post('/data-logging/register', 'register');
    Route::post('/data-logging/login', 'login');

    Route::get('/data-logging', 'pageLogging');
    Route::post('/data-logging', 'getAllDataLogging');
    Route::post('/data-logging/type', 'getDataLoggingByType');
    Route::post('/data-logging/type/time', 'getDataLoggingByTime');
    Route::delete('/data-logging/delete', 'deleteAllDataLogging');
    Route::delete('/data-logging/delete/type', 'deleteDataLoggingByType');
    Route::delete('/data-logging/delete/type/time', 'deleteDataLoggingByTime');
});
