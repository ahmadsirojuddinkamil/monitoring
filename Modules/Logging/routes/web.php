<?php

use Illuminate\Support\Facades\Route;
use Modules\Logging\App\Http\Controllers\LoggingController;

Route::controller(LoggingController::class)->group(function () {
    Route::middleware('auth_user')->group(function () {
        Route::get('/logging/{save_uuid_from_call}', 'viewMyLogging')->name('logging.view');
    });
});
