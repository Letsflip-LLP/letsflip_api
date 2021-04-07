<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;

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
});

$router->group(['middleware'=> ['admin_dashboard']], function($router){
    $router->group(['prefix' => 'dashboard'], function($router){
        Route::get('/', [AdminDashboardController::class,'index']);        
    });

    $router->group(['prefix' => 'user'], function($router){
        Route::get('/subscribers', [AdminDashboardController::class,'subscriberList']);  
        Route::post('/subscribers', [AdminDashboardController::class,'inviteSubscriber']);     
    });
}); 