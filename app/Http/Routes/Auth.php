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

$router->group(['middleware'=> ['form'],'prefix' => 'auth'], function($router){
    Route::get('peoples', [AuthController::class, 'peoples']);
    Route::get('test-socket', [AuthController::class, 'testSocket']);


    Route::post('register', [AuthController::class, 'register'])->name('PostAuthControllerRegister');
    Route::post('login', [AuthController::class, 'login'])->name('PostAuthControllerLogin');

    Route::post('login/google', [AuthController::class, 'loginGoogle'])->name('PostAuthControllerLoginGoogle');
    Route::post('login/facebook', [AuthController::class, 'loginFacebook'])->name('PostAuthControllerLoginFacebook');


    Route::post('request-reset-password', [AuthController::class, 'requestResetPassword'])->name('PostAuthControllerRequestResetPassword');
    Route::post('submit-reset-password', [AuthController::class, 'submitResetPassword'])->name('PostAuthControllerSubmitResetPassword');

    $router->get('email-verification', function (Request $request){
        return view('emails.account-verification',["reset_password_url"=> "http://www.facebok.com","email" => "andhi@email.com" , 'password' => "*****" , "activate_url" => "http://www.facebook.com" ,"first_name" => "ANd" , "last_name" => "sapu"]);
    }); 
}); 
 

