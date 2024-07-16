<?php

use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin'
], function ($router) {
    Route::group([
        'prefix' => 'user'
    ], function ($router) {
        Route::get('', [UserController::class, 'index']);
        Route::post('', [UserController::class, 'store']);
        Route::get('{id}', [UserController::class, 'show']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'destroy']);
    });
    Route::group([
        'prefix' => 'book'
    ], function ($router) {
        Route::get('', [AdminBookController::class, 'index']);
        Route::post('', [AdminBookController::class, 'store']);
        Route::post('{id}/picture', [AdminBookController::class, 'upload']);
        Route::get('unConfirmed', [AdminBookController::class, 'unConfirmed']);
        Route::get('{id}', [AdminBookController::class, 'show']);
        Route::put('{id}', [AdminBookController::class, 'update']);
        Route::put('verify/{id}', [AdminBookController::class, 'verifyBook']);
        Route::delete('{id}', [AdminBookController::class, 'destroy']);
    });
    Route::group([
        'prefix' => 'comment'
    ], function ($router) {
        Route::get('', [AdminCommentController::class, 'index']);
        Route::post('', [AdminCommentController::class, 'store']);
        Route::get('{id}', [AdminCommentController::class, 'show']);
        Route::put('{id}', [AdminCommentController::class, 'update']);
        Route::delete('{id}', [AdminCommentController::class, 'destroy']);
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
    'prefix' => 'book'
], function ($router) {
    Route::get('', [BookController::class, 'index']);
    Route::post('', [BookController::class, 'store']);
    Route::get('{id}', [BookController::class, 'show']);
    Route::put('{id}', [BookController::class, 'update']);
    Route::delete('{id}', [BookController::class, 'destroy']);
});

Route::group([
    'prefix' => 'category'
], function ($router) {
    Route::get('', [CategoryController::class, 'index']);
    Route::post('', [CategoryController::class, 'store']);
    Route::get('{id}', [CategoryController::class, 'show']);
    Route::put('{id}', [CategoryController::class, 'update']);
    Route::delete('{id}', [CategoryController::class, 'destroy']);
});
Route::group([
    'prefix' => 'quote'
], function ($router) {
    Route::get('', [QuotesController::class, 'quote']);
    Route::post('', [QuotesController::class, 'store']);
    Route::put('{id}', [QuotesController::class, 'update']);
    Route::delete('{id}', [QuotesController::class, 'destroy']);
});

Route::get('test', [ExampleController::class, 'test']);
Route::get('test1', [ExampleController::class, 'test1']);
Route::post('contact-us', [ContactUsController::class, 'contact']);

//Route::post('change-password',[AuthController::class,'changePassword']);

Route::group([
    'prefix' => 'comment'
], function ($router) {
    Route::get('', [CommentController::class, 'index']);
    Route::post('', [CommentController::class, 'store']);
    Route::get('top', [CommentController::class, 'topComments']);
    Route::put('{id}', [CommentController::class, 'update']);
    Route::delete('{id}', [CommentController::class, 'destroy']);
});

Route::group([
    'prefix' => 'ticket'
], function ($router) {
    Route::get('', [TicketController::class, 'index']);
    Route::get('open', [TicketController::class, 'open']);
    Route::post('', [TicketController::class, 'store']);
    Route::get('{id}', [TicketController::class, 'show']);
});
