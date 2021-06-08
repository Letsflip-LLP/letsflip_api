<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSubscriberController;
use App\Http\Controllers\Admin\AdminCompanyController;

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
        Route::get('/subscribers/resend-invitation/{id}', [AdminSubscriberController::class,'resendInviteSubscriber']);     
    });

    $router->group(['prefix' => 'company'], function($router){
        Route::get('/list', [AdminCompanyController::class,'companyList']);
        Route::post('/add', [AdminCompanyController::class,'companyAdd']);
        Route::get('/edit/{id}', [AdminCompanyController::class,'companyEdit']);
        Route::post('/submit-edit', [AdminCompanyController::class,'companySubmitEdit']);
    });
}); 