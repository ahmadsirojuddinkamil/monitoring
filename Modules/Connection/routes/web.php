<?php

use Illuminate\Support\Facades\Route;
use Modules\Connection\App\Http\Controllers\ConnectionController;

Route::controller(ConnectionController::class)->group(function () {
    Route::middleware('auth_administrator')->group(function () {
        Route::get('/connection/list', 'viewListConnection')->name('connection.list.view');
        Route::get('/connection/list/{save_uuid_from_call}', 'viewOtherConnection')->name('connection.list.other.view');
        Route::delete('/connection/list/{save_uuid_from_call}', 'delete')->name('connection.list.delete');
    });

    Route::middleware('auth_user')->group(function () {
        Route::get('/connection/create', 'create')->name('connection.create');
        Route::post('/connection/create', 'store')->name('connection.store');
        Route::get('/connection/{save_uuid_from_call}', 'viewMyConnection')->name('connection.view');
        Route::get('/connection/{save_uuid_from_call}/edit', 'edit')->name('connection.edit');
        Route::put('/connection/{save_uuid_from_call}', 'update')->name('connection.update');
    });
});
