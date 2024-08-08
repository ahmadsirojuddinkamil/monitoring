<?php

use Illuminate\Support\Facades\Route;
use Modules\Logging\App\Http\Controllers\LoggingController;

Route::controller(LoggingController::class)->group(function () {
    Route::middleware('connection_is_valid')->group(function () {
        Route::get('/logging/register', 'viewRegister')->name('logging.register');
        Route::post('/logging/register', 'storeRegister')->name('logging.store.register');
        Route::get('/logging/login', 'viewLogin')->name('logging.login');
        Route::post('/logging/login', 'storeLogin')->name('logging.store.login');
        Route::get('/logging/{uuid}', 'viewLoggingList')->name('logging.view');
        Route::get('/logging/{uuid}/search', 'searchLogging')->name('logging.view.search');
        Route::get('/logging/{uuid}/create', 'viewCreate')->name('logging.view.create');
        Route::post('/logging/{uuid}/store', 'storeCreate')->name('logging.store.create');
        Route::get('/logging/{uuid}/show', 'viewLogging')->name('logging.view.show');
        Route::get('/logging/{uuid}/download', 'downloadLogs')->name('logging.download.box');
        Route::get('/logging/{uuid}/{type_log}/download', 'downloadLog')->name('logging.download.type');
        Route::delete('/logging/{uuid}/delete', 'deleteLogging')->name('logging.delete');
    });
});
