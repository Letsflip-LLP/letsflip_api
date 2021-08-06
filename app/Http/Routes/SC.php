<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\SC\PostController;
use App\Http\Controllers\V1\SC\CommentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'createPost']);
        Route::post('{post_id}', [PostController::class, 'updatePost']);
        Route::get('{post_id}/delete', [PostController::class, 'deletePost']);

        Route::group(['prefix' => '{post_id}/comments', 'as' => 'comments.'], function () {
            Route::get('/', [CommentController::class, 'index']);
            Route::post('/', [CommentController::class, 'createComment']);
            Route::post('{comment_id}', [CommentController::class, 'updateComment']);
            Route::get('{comment_id}/delete', [CommentController::class, 'deleteComment']);
        });
    });
});
