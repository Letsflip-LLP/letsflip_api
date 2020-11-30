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

Route::middleware('auth:api')->get('self', function (Request $request){
    Route::post('login', [AuthController::class, 'login'])->name('PostAuthControllerLogin');
});

$router->group(['middleware'=> ['form'],'prefix' => 'auth'], function($router){
    Route::post('register', [AuthController::class, 'register'])->name('PostAuthControllerRegister');
    Route::post('login', [AuthController::class, 'login'])->name('PostAuthControllerLogin');

    $router->get('email-verification', function (Request $request){
        return view('emails.account-verification',['email' => 'emai@email.com', 'password' => '2222' , 'activate_url' => 'http://www.com.com']);
    });
}); 
 

