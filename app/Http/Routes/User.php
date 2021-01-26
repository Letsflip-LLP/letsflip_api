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
    $router->group(['middleware'=> ['auth:api','verified'] , 'prefix' => 'self' ], function($router){
        Route::get('/', [UserController::class, 'self'])->name('GetUserControllerSelf');
        Route::get('notification', [UserController::class, 'getSelfNotification'])->name('GetUserControllerSelf');
    });
});


$router->group(['middleware'=> [],'prefix' => 'user'], function($router){
    Route::get('list', [UserController::class, 'getPublicList'])->name('GetUserPublictList'); 
});

 
 

