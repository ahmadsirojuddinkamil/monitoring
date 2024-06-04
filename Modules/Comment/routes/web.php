<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\App\Http\Controllers\CommentController;

Route::controller(CommentController::class)->group(function () {
    Route::middleware('auth_user')->group(function () {
        Route::post('/comment', 'store')->name('comment');
    });

    Route::middleware('auth_administrator')->group(function () {
        Route::get('/comment/list', 'viewCommentList')->name('view.comment.list');
        Route::get('/comment/{save_uuid_from_call}/edit', 'viewEdit')->name('comment.edit');
        Route::put('/comment/{save_uuid_from_call}', 'update')->name('comment.update');
        Route::delete('/comment/{save_uuid_from_call}', 'delete')->name('comment.delete');
    });
});
