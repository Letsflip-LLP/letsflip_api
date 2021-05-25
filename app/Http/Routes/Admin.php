<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSubscriberController;
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

$router->group(['prefix' => 'auth'], function($router){
    Route::get('login', [AdminAuthController::class, 'login']);
    Route::post('login', [AdminAuthController::class, 'postLogin']);

    Route::get('logout', [AdminAuthController::class, 'logout']);
    Route::post('logout', [AdminAuthController::class, 'logout']);
});

$router->group(['middleware'=> ['admin_dashboard']], function($router){
    $router->group(['prefix' => 'dashboard'], function($router){
        Route::get('/', [AdminDashboardController::class,'index']);        
    });

    $router->group(['prefix' => 'user'], function($router){
        Route::get('/subscribers', [AdminSubscriberController::class,'subscriberList']);  
        Route::post('/subscribers', [AdminSubscriberController::class,'inviteSubscriber']);     

        // EDIT SUB 
        Route::get('/subscribers/edit/{id}', [AdminSubscriberController::class,'subscriberEdit']);     
        Route::post('/subscribers/edit/{id}', [AdminSubscriberController::class,'subscriberSubmitEdit']);     
    });
}); 