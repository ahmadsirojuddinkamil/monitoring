<?php

use Illuminate\Support\Facades\Route;
use Modules\Logging\App\Http\Controllers\LoggingController;

Route::controller(LoggingController::class)->group(function () {
    Route::middleware('connection_is_valid')->group(function () {
        Route::get('/logging/register', 'viewRegister')->name('logging.register');
        Route::post('/logging/register', 'storeRegister')->name('logging.store.register');
        Route::get('/logging/login', 'viewLogin')->name('logging.login');
        Route::post('/logging/login', 'storeLogin')->name('logging.store.login');
        Route::get('/logging/{save_uuid_from_call}', 'viewLoggingList')->name('logging.view');
        Route::get('/logging/{save_uuid_from_call}/search', 'searchLogging')->name('logging.view.search');
        Route::get('/logging/{save_uuid_from_call}/create', 'viewCreate')->name('logging.view.create');
        Route::post('/logging/{save_uuid_from_call}/store', 'storeCreate')->name('logging.store.create');
        Route::get('/logging/{save_uuid_from_call}/show', 'viewLogging')->name('logging.view.show');
        Route::get('/logging/{save_uuid_from_call}/{save_type_log_from_call}/download', 'downloadLog')->name('logging.download.file');
    });
});
