<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\MissionController;
use App\Http\Controllers\V1\ClassRoomController;

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

Route::get('/subscription/accept-invitation', [AuthController::class, 'subsAcceptInvitation']);


$router->group(['middleware'=> ['form'] ], function($router){
    $router->group(['prefix' => 'account'], function($router){
        Route::get('verification/verify', [AuthController::class, 'verificationAccount'])->name('GetAuthControllerverificationAccount');
        Route::get('confirm-reset-password/forgot', [AuthController::class, 'confirmResetPassword'])->name('GetAuthControllerConfirmResetPassword');
    });

    $router->group(['prefix' => 'open-app'], function($router){
        Route::get('mission/{mission_id}', [MissionController::class, 'openApp'])->name('GetMissionControllerOpenApp');
        Route::get('respones/{mission_id}', [MissionController::class, 'openApp'])->name('GetResponeControllerOpenApp');
        Route::get('classroom/{classroom_id}', [ClassRoomController::class, 'openAppClassroom'])->name('GetClassroomControllerOpenApp');
    });
}); 