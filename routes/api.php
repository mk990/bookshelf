<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\QuotesController;
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
    Route::get('forgotPassword/{token}', [AuthController::class, 'getForgotPassword'])->name('forgot.password');
    Route::post('forgotPassword/{token}', [AuthController::class, 'setForgotPassword']);
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
Route::post('contact-us', [ContactUsController::class, 'contact']);

//Route::post('change-password',[AuthController::class,'changePassword']);
