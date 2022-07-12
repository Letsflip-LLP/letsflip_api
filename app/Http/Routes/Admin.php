<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSubscriberController;
use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\Admin\AdminMissionController;
use App\Http\Controllers\Admin\AdminSystemController;

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

$router->group(['prefix' => 'auth'], function ($router) {
    Route::get('login', [AdminAuthController::class, 'login']);
    Route::post('login', [AdminAuthController::class, 'postLogin']);

    Route::get('logout', [AdminAuthController::class, 'logout']);
    Route::post('logout', [AdminAuthController::class, 'logout']);
});

$router->group(['middleware' => ['admin_dashboard']], function ($router) {
    $router->group(['prefix' => 'dashboard'], function ($router) {
        Route::get('/', [AdminDashboardController::class, 'index']);
    });

    $router->group(['prefix' => 'user'], function ($router) {
        Route::get('/users', [AdminSystemController::class, 'userList']);
        Route::get('/users/edit/{id}', [AdminSystemController::class, 'userEdit']);
        Route::post('/users/edit', [AdminSystemController::class, 'userSubmitEdit']);
        // Route::get('/users/delete/{id}', [AdminSystemController::class, 'userSubmitDelete']);

        $router->group(['prefix' => 'mission'], function ($router) {
            Route::get('/{id}', [AdminSystemController::class, 'userMission']);
            Route::get('/answers/{id}', [AdminSystemController::class, 'missionAnswer']);
            Route::get('/questions/{id}', [AdminSystemController::class, 'missionQuestion']);
            Route::get('/comments/{id}', [AdminSystemController::class, 'missionComment']);
            Route::get('/responses/{id}', [AdminSystemController::class, 'missionResponse']);
            Route::get('/responsecomments/{id}', [AdminSystemController::class, 'missionResponseComment']);
        });
        
        Route::get('/subscribers', [AdminSubscriberController::class, 'subscriberList']);
        Route::post('/subscribers', [AdminSubscriberController::class, 'inviteSubscriber']);

        // EDIT SUB 
        Route::get('/subscribers/edit/{id}', [AdminSubscriberController::class, 'subscriberEdit']);
        Route::post('/subscribers/edit/{id}', [AdminSubscriberController::class, 'subscriberSubmitEdit']);
        Route::get('/subscribers/resend-invitation/{id}', [AdminSubscriberController::class, 'resendInviteSubscriber']);
    });

    $router->group(['prefix' => 'reported'], function ($router) {
        // Reported Content
        Route::get('/content', [AdminSystemController::class, 'contentReportedList']);
        Route::get('/open-content/{id}', [AdminSystemController::class, 'openContent']);
        Route::get('/details/{id}', [AdminSystemController::class, 'reportedDetails']);
        Route::get('/take-down/{id}', [AdminMissionController::class, 'takeDown']);

        // Reported User
        Route::get('/user', [AdminSubscriberController::class, 'userReportedList']);
        Route::get('/user/details/{id}', [AdminSubscriberController::class, 'userReportedDetails']);
        // Route::get('/user/block/{id}', [AdminSystemController::class, 'blockUserAction']);
    });

    $router->group(['prefix' => 'company'], function ($router) {
        Route::get('/', [AdminCompanyController::class, 'companyList']);
        Route::post('/add', [AdminCompanyController::class, 'companyAdd']);
        Route::get('/edit/{id}', [AdminCompanyController::class, 'companyEdit']);
        Route::post('/submit-edit', [AdminCompanyController::class, 'companySubmitEdit']);
    });

    $router->group(['prefix' => 'system'], function ($router) {
        Route::get('/prices', [AdminSystemController::class, 'priceList']);
        Route::get('/prices/edit/{id}', [AdminSystemController::class, 'priceEdit']);
        Route::post('/prices/edit', [AdminSystemController::class, 'priceEditSubmit']);
        Route::post('/prices/add', [AdminSystemController::class, 'priceAdd']);
    });
});
