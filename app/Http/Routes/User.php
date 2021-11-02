<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\UserController;

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
 
$router->group(['middleware'=> ['auth:api','verified'],'prefix' => 'user'], function($router){

    // Need Login
    $router->group(['middleware'=> ['auth:api','verified']], function($router){
        Route::post('subscribe', [UserController::class, 'subscribePremiumAccount'])->name('PostUserControllerSubscribePremiumAccount'); 

        // GET USER LIST
        Route::get('list', [UserController::class, 'getPublicList'])->name('GetUserPublictList'); 

        $router->group(['prefix' => 'self' ], function($router){
            Route::post('/update-profile', [UserController::class, 'userSelfUpdateProfile'])->name('PostUpdateProfileUserControllerUserSelfUpdateProfile');

            Route::get('/', [UserController::class, 'self'])->name('GetUserControllerSelf');

            Route::get('/feelings-option', [UserController::class, 'feelingAvailableOption'])->name('GetUserControllerFeelingAvailableOption');
            Route::post('/feelings/update', [UserController::class, 'updateDailyFeeling'])->name('PostUserUserControllerUpdateDailyFeeling');

            Route::get('notification', [UserController::class, 'getSelfNotification'])->name('GetUserControllerSelf');
        
            Route::post('/follow/action', [UserController::class, 'userFollowAction'])->name('GetUserControllerAddUserFollow');

            Route::post('/blocked/action', [UserController::class, 'userBlockedAction'])->name('GetUserControllerAddUserFollow');

            Route::get('summary-update', [UserController::class, 'getSelfSummaryUpdate'])->name('GetUserControllerGetSelfSummaryUpdate');

            Route::post('/logout', [UserController::class, 'logout'])->name('GetUserControllerLogout');
        });
    });
});


$router->group(['middleware'=> [],'prefix' => 'user'], function($router){
     
    Route::get('detail', [UserController::class, 'getPublicDetailUser'])->name('GetPublicUserDetail'); 
    Route::get('availlable-social-media', [UserController::class, 'availlableSocialMedia'])->name('GetUserControllerAvaillableSocialMedia'); 

    // PRODUCT
    Route::get('product/detail', [UserController::class, 'getProductPremiumDetail'])->name('GetUserControllergetProductPremiumDetail'); 
 });

 
 

