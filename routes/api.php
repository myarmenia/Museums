<?php

use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\Courses\CourseLanguagesController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\Lessons\LessonController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SendOrderController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\Email\SendYourQuestionController;
use App\Http\Controllers\API\TrialCourseController;
use App\Http\Controllers\API\Lessons\UserCurrentLessonController;
use App\Http\Controllers\API\Student\DashboardController;
use App\Http\Controllers\API\Student\VisitHistoryController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\Email\SendFeedbackController;
use App\Http\Controllers\Email\SendClientProjectDetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function ($router) {
  Route::group(['middleware' => ['setlang']], function ($router) {

    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('signup-google', [AuthController::class, 'signupGoogle']);
        Route::post('check-verify-token', [AuthController::class, 'checkVerifyToken']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('resend-verify', [AuthController::class, 'resendVerify']);
    });

    Route::group(['middleware' => 'apiAuthCheck'], function ($router) {
        Route::get('course-language',[CourseLanguagesController::class,'index']);
        Route::get('language-lessons/{id}',[LessonController::class,'languageLessons']);
        Route::get('user-current-lesson/',[UserCurrentLessonController::class,'index']);

        Route::group(['prefix' => 'user'], function ($router) {
            Route::post('edit', [UserController::class, 'edit']);
            Route::post('editPassword', [UserController::class, 'editPassword']);
        });
    });

    Route::group(['prefix' => 'project'], function ($router) {
        Route::get('getProject', [ProjectController::class, 'getProject']);
    });

    Route::group(['prefix' => 'email'], function ($router) {
        Route::post('feedback', SendFeedbackController::class);
        Route::post('clientProject', SendClientProjectDetController::class);
        Route::post('clientQuestion', SendYourQuestionController::class);
    });

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
    Route::post('check-forgot-token', [ForgotPasswordController::class, 'checkForgotToken']);
    Route::post('send-new-password', [ForgotPasswordController::class, 'sendNewPassword']);
    Route::post('resend-forgot', [ForgotPasswordController::class, 'resendForgot']);
    Route::post('trial-course', [TrialCourseController::class, 'trialCourse']);
    Route::post('send-order', SendOrderController::class);



    // Route::get('dashboard',[DashboardController::class,'index']);
    // Route::get('home',[HomeController::class,'home']);
    // Route::get('visit-history',[VisitHistoryController::class,'index']);

    Route::group(['prefix' => 'news'], function ($router) {
        Route::get('get-news', [NewsController::class, 'getNewslist']);
        Route::get('get-news/{id}', [NewsController::class, 'getNews']);
    });

  });
  Route::get('test-museum',[TestController::class, 'test']);


});
