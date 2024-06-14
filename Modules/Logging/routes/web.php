<?php

use Illuminate\Support\Facades\Route;
use Modules\Logging\App\Http\Controllers\LoggingController;

Route::controller(LoggingController::class)->group(function () {
    Route::middleware('connection_is_valid')->group(function () {
        Route::get('/logging/login', 'viewLogin')->name('logging.login');
        Route::get('/logging/register', 'viewRegister')->name('logging.register');
        Route::post('/logging/register', 'storeRegister')->name('logging.create.register');
        Route::get('/logging/{save_uuid_from_call}', 'viewMyLogging')->name('logging.view');
        Route::get('/logging/{save_uuid_from_call}/search', 'searchLogging')->name('logging.view.search');
        Route::get('/logging/{save_uuid_from_call}/create', 'viewCreate')->name('logging.view.create');
    });
});
