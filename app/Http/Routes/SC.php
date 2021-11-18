<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\SC\PostController;
use App\Http\Controllers\V1\SC\CommentController;
use App\Http\Controllers\V1\SC\FriendsController;

use App\Http\Controllers\V1\SC\ServerController;
use App\Http\Controllers\V1\SC\RoomCategoryController;
use App\Http\Controllers\V1\SC\RoomChannelController;
use App\Http\Controllers\V1\SC\RoomMemberController;
use App\Http\Controllers\V1\SC\RoomMemberTypeController;
use App\Http\Controllers\V1\SC\RoomMessageController;

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
        Route::get('/detail', [PostController::class, 'detailPost']);
        // Route::post('{post_id}', [PostController::class, 'updatePost']);
        Route::post('delete', [PostController::class, 'deletePost']);

        Route::group(['prefix' => 'like'], function () {
            Route::post('/', [PostController::class, 'likePost']); 
        });

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
            Route::post('edit', [ServerController::class, 'edit'])->name('edit');
            Route::post('delete', [ServerController::class, 'delete'])->name('delete');
            Route::get('detail', [ServerController::class, 'detail'])->name('detail');
        });

        Route::get('categories', [RoomCategoryController::class, 'index'])->name('category.index');
        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::post('add', [RoomCategoryController::class, 'add'])->name('add');
            Route::post('edit', [RoomCategoryController::class, 'edit'])->name('edit');
            Route::post('delete', [RoomCategoryController::class, 'delete'])->name('delete');
            Route::get('detail', [RoomCategoryController::class, 'detail'])->name('detail');
        });

        Route::get('channels', [RoomChannelController::class, 'index'])->name('channel.index');
        Route::group(['prefix' => 'channel', 'as' => 'channel.'], function () {
            Route::post('add', [RoomChannelController::class, 'add'])->name('add');
            Route::post('edit', [RoomChannelController::class, 'edit'])->name('edit');
            Route::post('delete', [RoomChannelController::class, 'delete'])->name('delete');
            Route::get('detail', [RoomChannelController::class, 'detail'])->name('detail');

            Route::get('members', [RoomMemberController::class, 'index'])->name('member.index');
            Route::group(['prefix' => 'member', 'as' => 'member.'], function () {
                Route::post('add', [RoomMemberController::class, 'add'])->name('add');
                Route::post('edit', [RoomMemberController::class, 'edit'])->name('edit');
                Route::post('delete', [RoomMemberController::class, 'delete'])->name('delete');
                Route::get('detail', [RoomMemberController::class, 'detail'])->name('detail');
            });

            //
            Route::get('messages', [RoomMessageController::class, 'index'])->name('message.index');
            Route::group(['prefix' => 'message', 'as' => 'message.'], function () {
                Route::post('send', [RoomMessageController::class, 'add'])->name('add');
                Route::post('edit', [RoomMessageController::class, 'edit'])->name('edit');
                Route::post('delete', [RoomMessageController::class, 'delete'])->name('delete');
            });
        });

        Route::get('member-types', [RoomMemberTypeController::class, 'index'])->name('member-type.index');
        Route::group(['prefix' => 'member-type', 'as' => 'member-type.'], function () {
            Route::post('add', [RoomMemberTypeController::class, 'add'])->name('add');
            Route::post('edit', [RoomMemberTypeController::class, 'edit'])->name('edit');
            Route::post('delete', [RoomMemberTypeController::class, 'delete'])->name('delete');
            Route::get('detail', [RoomMemberTypeController::class, 'detail'])->name('detail');
        });
    });
});
