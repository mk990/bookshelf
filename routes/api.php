<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin'
], function ($router) {
    Route::group([
        'prefix' => 'user'
    ], function ($router) {
        Route::apiResource('', UserController::class);
    });
    Route::group([
        'prefix' => 'book'
    ], function ($router) {
        Route::apiResource('', AdminBookController::class);
        Route::post('{id}/picture', [AdminBookController::class, 'upload']);
        Route::get('unConfirmed', [AdminBookController::class, 'unConfirmed']);
        Route::put('verify/{id}', [AdminBookController::class, 'verifyBook']);
    });
    Route::group([
        'prefix' => 'comment'
    ], function ($router) {
        Route::apiResource('', AdminCommentController::class);
    });
    Route::group([
        'prefix' => 'ticket'
    ], function ($router) {
        Route::apiResource('', AdminTicketController::class);
        Route::get('open', [AdminTicketController::class, 'open']);
        Route::get('close', [AdminTicketController::class, 'closedTicket']);
    });
    Route::group(['prefix'=>'messages'], function () {
        Route::apiResource('', AdminTicketController::class);
        Route::get('{id}', [AdminMessageController::class, 'Messages']);
        Route::delete('{id}', [AdminMessageController::class, 'destroy']);
    });
});

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::get('verify-email', [AuthController::class, 'verifyEmail']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmailAddress'])->name('verification.verify');
    Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::get('forgotPassword/{token}', [AuthController::class, 'getForgotPassword'])->name('password.reset');
    Route::post('forgotPassword/{token}', [AuthController::class, 'setForgotPassword'])->name('change-password');
});

Route::group([
    'prefix' => 'quote'
], function ($router) {
    Route::apiResource('', QuotesController::class);
});

Route::get('test', [ExampleController::class, 'test']);
Route::get('test1', [ExampleController::class, 'test1']);
Route::post('contact-us', [ContactUsController::class, 'contact']);

Route::group([
    'prefix' => 'comment'
], function ($router) {
    Route::apiResource('', CommentController::class);
    Route::get('top', [CommentController::class, 'topComments']);
});

Route::group([
    'prefix' => 'ticket'
], function ($router) {
    Route::apiResource('', TicketController::class);
    Route::get('open', [TicketController::class, 'open']);
    Route::get('close', [TicketController::class, 'closedTicket']);
    Route::post('{id}/close', [TicketController::class, 'closeTicket']);
});

Route::group([
    'prefix' => 'messages'
], function () {
    Route::apiResource('', MessageController::class);
    Route::get('{id}', [MessageController::class, 'Messages']);
});

Route::group(['prefix'=>'blog'], function () {
    Route::apiResource('', BlogController::class);
});

Route::group(['prefix'=>'category'], function () {
    Route::apiResource('', CategoryController::class);
});

Route::group(['prefix'=>'book'], function () {
    Route::apiResource('', BookController::class);
});

Route::group([
    'prefix' => 'home'
], function ($router) {
    Route::get('/categories', [HomeController::class, 'categories']);
    Route::get('/book', [HomeController::class, 'book']);
    Route::get('/qoutes', [HomeController::class, 'qoutes']);
});
