<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\App\Http\Controllers\DashboardController;

Route::middleware('auth_user')->controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'viewDashboard')->name('view.dashboard');
});
