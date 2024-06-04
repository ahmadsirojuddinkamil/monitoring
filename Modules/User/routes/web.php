<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\UserController;

Route::controller(UserController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', 'viewRegister')->name('view.register');
        Route::post('/register', 'register')->name('register');
        Route::get('/login', 'viewLogin')->name('view.login');
        Route::post('/login', 'login')->name('login');
    });

    Route::middleware('auth_user')->group(function () {
        Route::get('/profile/{save_uuid_from_call}', 'viewProfile')->name('view.profile');
        Route::put('/profile/{save_uuid_from_call}', 'profileUpdate')->name('profile.update');
        Route::post('/logout', 'logout')->name('logout');
    });

    Route::middleware('auth_administrator')->group(function () {
        Route::get('/user/list', 'viewUserList')->name('view.user.list');
        Route::get('/user/{save_uuid_from_call}/edit', 'viewEdit')->name('view.edit');
        Route::put('/user/{save_uuid_from_call}', 'edit')->name('edit');
        Route::delete('/user/{save_uuid_from_call}', 'delete')->name('delete.user');
    });
});
