<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\MissionController;
use App\Http\Controllers\V1\ClassRoomController;
use App\Http\Controllers\V1\MissionCommentController; 
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
 
$router->group(['middleware'=> [],'prefix' => 'mission'], function($router){


    #STORAGE
    $router->group(['prefix' => 'storage','middleware'=> ['form']], function($router){
        Route::post('upload', [StorageController::class, 'uploadFile'])->name('PostStorageControlleruploadFile'); 
    });


    // Need Login
    $router->group(['middleware'=> ['auth:api','verified']], function($router){
        // MISSION
        Route::post('add', [MissionController::class, 'addMission'])->name('PostMissionControllerAddMission'); 
        Route::post('delete', [MissionController::class, 'deleteMission'])->name('PostMissionControllerDeleteMission'); 
        Route::post('edit', [MissionController::class, 'editMission'])->name('PostMissionControllerEditMission'); 

        // COMMENTS
        Route::post('comment/add',      [MissionCommentController::class, 'addComment'])->name('PostMissionCommentControllerAddComment');
        Route::post('comment/delete',   [MissionCommentController::class, 'deleteComment'])->name('PostMissionCommentControllerDeleteComment');

        // LIKES 
        Route::post('action-like',      [   MissionController::class, 'likeActionMission'])->name('PostMissionControllerlikeActionMission');

        // REPORT 
        Route::post('report-content',   [MissionController::class, 'reportActionContent'])->name('PostMissionControllerReportActionContent');


        // REVIEW MISSION
        $router->group(['prefix' => 'review'], function($router){
            Route::post('add-review', [MissionController::class, 'addReview'])->name('PostMissionControllerAddReviewMission');
            Route::get('get-review-emotions', [MissionController::class, 'getReviewEmotions'])->name('GetMissionControllerGetReviewEmotions');
        });
        
        // Response Mission
        $router->group(['prefix' => 'respone'], function($router){
            // RESPONE
            Route::post('add', [MissionController::class, 'addResponeMission'])->name('PostMissionResponeControllerAddRespone'); 
            Route::post('delete', [MissionController::class,'deleteResponeMission'])->name('PostMissionControllerDeleteResponeMission'); 
            Route::post('edit', [MissionController::class,'editResponeMission'])->name('PostMissionControllerEditResponeMission'); 
            Route::get('detail', [MissionController::class,'getDetailResponeMission'])->name('GetMissionControllerGetDetailResponeMission'); 

            // COMMENT
            Route::post('comment/add', [MissionCommentController::class, 'addCommentResponeMission'])->name('PostMissionCommentControllerAddCommentResponeMission'); 
            Route::get('comment/list', [MissionCommentController::class, 'getCommentResponeMission'])->name('PostMissionCommentControllerGetCommentResponeMission'); 
            Route::post('comment/delete', [MissionCommentController::class, 'deleteCommentResponeMission'])->name('PostMissionCommentControllerDeleteCommentResponeMission');
        
            // GRADE
            Route::post('grading/add-preview', [MissionController::class, 'addGradingPreview'])->name('PostMissionControllerAddGradingPreview');
            Route::get('grading/get-answer',  [MissionController::class, 'getAnswerResponseGrade'])->name('GetMissionControllerGetAnswerResponseGrade');
            Route::get('grading/get-preview', [MissionController::class, 'getGradingPreview'])->name('GettMissionControllerGetGradingPreview');
            Route::post('grading/submit-grade-answer', [MissionController::class, 'addSubmitGradeAnswer'])->name('PostMissionControllerAddSubmitGradeAnswer');
        });

        $router->group(['prefix' => 'question'], function($router){
            Route::get('list', [MissionController::class, 'getQuestionList'])->name('GetMissionControllerGetQuestionList');
            Route::get('template', [MissionController::class, 'getQuestionTemplate'])->name('GetMissionControllerGetQuestionTemplate');
            Route::post('answer',   [MissionController::class, 'submitAnswerOfQuestion'])->name('PostMissionControllerSubmitAnswerOfQuestion');
        });

    });

 
    // Login Not Required
    Route::get('respone/list', [MissionController::class, 'getResponeMission'])->name('GetMissionControllerGetResponeMission'); 
    Route::get('comment/list',   [MissionCommentController::class, 'getComments'])->name('GetMissionCommentControllerGetComments');
    Route::get('list',   [MissionController::class, 'getMission'])->name('GetMissionControllerGetMission');
    Route::get('detail', [MissionController::class, 'getMissionDetail'])->name('GetMissionControllerGetMissionDetail');
});

$router->group(['middleware'=> [],'prefix' => 'classroom'], function($router){

    // Need Login
    $router->group(['middleware'=> ['auth:api','verified']], function($router){
        Route::post('add', [ClassRoomController::class, 'addClassRoom'])->name('PostClassRoomControllerAddClassRoom');
        Route::post('delete', [ClassRoomController::class, 'deleteClassRoom'])->name('PostClassRoomControllerDeleteClassRoom');
        
        // SUBSCRIBE CLASSROOM
        // Route::post('subscribe', [ClassRoomController::class, 'subscribeClassroom'])->name('PostClassRoomControllerSubscribeClassroom');

        Route::post('get-access', [ClassRoomController::class, 'getAccessClassRoom'])->name('PostClassRoomControllerGetAccess');
        Route::post('give-access', [ClassRoomController::class, 'giveAccessClassRoom'])->name('PostClassRoomControllerGiveAccessClassRoom');
    });

    // Login Not Required
    Route::get('list', [ClassRoomController::class, 'getClassRoom'])->name('GetClassRoomControllerGetClassRoom');
    Route::get('detail', [ClassRoomController::class, 'getDetailClassRoom'])->name('GetClassRoomControllerGetDetailClassRoom');
});

 
 
 

