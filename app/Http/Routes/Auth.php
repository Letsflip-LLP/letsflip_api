<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\StorageController;

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

$router->group(['middleware'=> ['form'],'prefix' => 'auth'], function($router){
    Route::get('peoples', [AuthController::class, 'peoples']);


    Route::post('register', [AuthController::class, 'register'])->name('PostAuthControllerRegister');
    Route::post('login', [AuthController::class, 'login'])->name('PostAuthControllerLogin');

    Route::post('login/google', [AuthController::class, 'loginGoogle'])->name('PostAuthControllerLoginGoogle');


    Route::post('request-reset-password', [AuthController::class, 'requestResetPassword'])->name('PostAuthControllerRequestResetPassword');
    Route::post('submit-reset-password', [AuthController::class, 'submitResetPassword'])->name('PostAuthControllerSubmitResetPassword');

    $router->get('email-verification', function (Request $request){
        return view('emails.account-reset-pass-confirmation',['full_name' => 'andhi saputro', 'reset_password_url' => 'http://www.com.com']);
    });

    $router->group(['prefix' => 'storage'], function($router){
        Route::post('upload', [StorageController::class, 'uploadFile'])->name('PostStorageControlleruploadFile'); 
    });
}); 
 

