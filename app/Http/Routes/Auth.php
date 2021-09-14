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

$router->group(['middleware' => ['form'], 'prefix' => 'auth'], function ($router) {

    Route::get('check-update', [AuthController::class, 'checkAppUpdate']);

    Route::get('peoples', [AuthController::class, 'peoples']);
    Route::get('test-socket', [AuthController::class, 'testSocket']);


    Route::post('register', [AuthController::class, 'register'])->name('PostAuthControllerRegister');
    Route::post('register/verify', [AuthController::class, 'registerVerify'])->name('PostAuthControllerRegisterVerify');

    Route::post('register/resend-email-verify', [AuthController::class, 'resendEmailVerify'])->name('PostAuthControllerResendEmailVerify');

    Route::post('login', [AuthController::class, 'login'])->name('PostAuthControllerLogin');

    Route::post('login/google', [AuthController::class, 'loginGoogle'])->name('PostAuthControllerLoginGoogle');
    Route::post('login/facebook', [AuthController::class, 'loginFacebook'])->name('PostAuthControllerLoginFacebook');
    Route::post('login/apple', [AuthController::class, 'loginApple'])->name('PostAuthControllerLoginApple');


    Route::post('request-reset-password', [AuthController::class, 'requestResetPassword'])->name('PostAuthControllerRequestResetPassword');
    Route::post('submit-reset-password', [AuthController::class, 'submitResetPassword'])->name('PostAuthControllerSubmitResetPassword');

    Route::post('request-forgot-password', [AuthController::class, 'forgotPasswordRequest'])->name('PostAuthControllerForgotPasswordRequest');
    Route::post('submit-forgot-password', [AuthController::class, 'forgotPasswordSubmit'])->name('PostAuthControllerForgotPasswordSubmit');

    $router->get('email-verification', function (Request $request) {
        \Mail::to("andhi.saputro1508@gmail.com")->queue(new \App\Mail\verificationUserRegister(["reset_password_url" => "http://www.facebok.com", "email" => "andhi@email.com", 'password' => "*****", "activate_url" => "http://www.facebook.com", "first_name" => "ANd", "last_name" => "sapu"]));
        return view('emails.account-verification', ["reset_password_url" => "http://www.facebok.com", "email" => "andhi@email.com", 'password' => "*****", "activate_url" => "http://www.facebook.com", "first_name" => "ANd", "last_name" => "sapu"]);
    });
});
