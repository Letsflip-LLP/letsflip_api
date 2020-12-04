<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\V1\AuthController;
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

$router->group(['middleware'=> ['form'] ], function($router){
    $router->group(['prefix' => 'account'], function($router){
        Route::get('verification', [AuthController::class, 'verificationAccount'])->name('GetAuthControllerverificationAccount');
        Route::get('confirm-reset-password', [AuthController::class, 'confirmResetPassword'])->name('GetAuthControllerConfirmResetPassword');
    });
}); 