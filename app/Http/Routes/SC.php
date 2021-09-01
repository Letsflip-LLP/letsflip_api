<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\SC\PostController;
use App\Http\Controllers\V1\SC\CommentController;
use App\Http\Controllers\V1\SC\FriendsController;

use App\Http\Controllers\V1\SC\ServerController;
use App\Http\Controllers\V1\SC\ServerCategoryController;

use App\Http\Controllers\V1\SC\ChannelController;
use App\Http\Controllers\V1\SC\ChannelMemberTypeController;
use App\Http\Controllers\V1\SC\ChannelMemberController;
// use App\Http\Controllers\V1\SC\ServerCategoryController;
// use App\Http\Controllers\V1\SC\ServerCategoryController;


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
 
Route::get('bebek', function () {
    return 'ini api';
});

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    // Route::get('home', [PostController::class, 'home']);
    Route::group(['prefix' => 'friends', 'as' => 'friends.'], function () {
        Route::get('/', [FriendsController::class, 'list']);
        Route::get('invitation', [FriendsController::class, 'invitation']);
        Route::post('add', [FriendsController::class, 'add']);
        Route::post('accept', [FriendsController::class, 'accept']);
        Route::post('remove', [FriendsController::class, 'remove']);
        // Route::post('confirm', [FriendsController::class, 'confirm']);
    });

    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'createPost']);
        // Route::post('{post_id}', [PostController::class, 'updatePost']);
        Route::get('delete', [PostController::class, 'deletePost']);

        Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
            Route::get('/', [CommentController::class, 'index']);
            Route::post('/', [CommentController::class, 'createComment']);
            Route::post('delete', [CommentController::class, 'createComment']);
            // Route::post('{comment_id}', [CommentController::class, 'updateComment']);
            // Route::get('{comment_id}/delete', [CommentController::class, 'deleteComment']);
        });
    });

    Route::group(['prefix' => 'room', 'as' => 'room.'], function () {
        Route::get('servers', [ServerController::class, 'index']);
        Route::group(['prefix' => 'server', 'as' => 'server.'], function () {
            Route::post('add', [ServerController::class, 'add']);
            Route::post('edit', [ServerController::class, 'edit']);
            Route::post('delete', [ServerController::class, 'delete']);
            Route::get('detail', [ServerController::class, 'detail']);
        });

        Route::get('categories', [ServerCategoryController::class, 'index']);
        Route::group(['prefix' => 'category', 'as' => 'server.'], function () {
            Route::post('add', [ServerCategoryController::class, 'index']);
            Route::post('edit', [ServerCategoryController::class, 'index']);
            Route::post('delete', [ServerCategoryController::class, 'index']);
            Route::get('detail', [ServerCategoryController::class, 'index']);
        });
        // Route::get('/', [ServerController::class, 'index']);
        // Route::get('/', [ServerController::class, 'index']);
        // Route::get('/', [ServerController::class, 'index']);
        // Route::get('/', [ServerController::class, 'index']);
    });
});
