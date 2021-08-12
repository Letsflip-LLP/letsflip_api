<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\SC\PostController;
use App\Http\Controllers\V1\SC\CommentController;
use App\Http\Controllers\V1\SC\FriendsController;

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

// Route::get('vw', function () {
//     $ar = [
//         'first_name' => 'aaa',
//         'last_name' => 'aaa',
//         'reset_password_url' => 'aaa',
//         'email' => 'aaa',
//         'password' => 'aaa',
//         'activate_url' => 'aaa',
//         'account_type' => 'aaa',
//         'url' => 'aaa',
//         'message' => 'aaa',
//         'activate_url' => 'aaa',
//         'activate_url' => 'aaa',
//         'activate_url' => 'aaa',
//     ];
//     return view('accounts.confirmation-ress-pass', $ar);
// });

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    // Route::get('home', [PostController::class, 'home']);
    Route::group(['prefix' => 'friends', 'as' => 'friends.'], function () {
        Route::get('/', [FriendsController::class, 'list']);
        Route::get('invitation', [FriendsController::class, 'invitation']);
        Route::post('add', [FriendsController::class, 'add']);
        Route::post('remove', [FriendsController::class, 'remove']);
    });

    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'createPost']);
        // Route::post('{post_id}', [PostController::class, 'updatePost']);
        Route::get('delete', [PostController::class, 'deletePost']);

        Route::group(['prefix' => '{post_id}/comments', 'as' => 'comments.'], function () {
            Route::get('/', [CommentController::class, 'index']);
            Route::post('/', [CommentController::class, 'createComment']);
            Route::post('delete', [CommentController::class, 'createComment']);
            // Route::post('{comment_id}', [CommentController::class, 'updateComment']);
            // Route::get('{comment_id}/delete', [CommentController::class, 'deleteComment']);
        });
    });
});
