<?php

use Illuminate\Support\Facades\Route;
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
 
$router->group(['middleware'=> ['auth:api','verified'],'prefix' => 'mission'], function($router){
    Route::post('add', [MissionController::class, 'addMission'])->name('PostMissionControllerAddMission');
    Route::get('list', [MissionController::class, 'getMission'])->name('GetMissionControllerGetMission');
});

$router->group(['middleware'=> ['auth:api','verified'],'prefix' => 'classroom'], function($router){
    Route::post('add', [ClassRoomController::class, 'addClassRoom'])->name('PostClassRoomControllerAddClassRoom');
    Route::get('list', [ClassRoomController::class, 'getClassRoom'])->name('GetClassRoomControllerGetClassRoom');
});
 
 

