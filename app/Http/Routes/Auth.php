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
    dd(auth()->user()->email);
});

$router->group(['prefix' => 'auth'], function($router){
    Route::post('register', [AuthController::class, 'register']);
});
 

