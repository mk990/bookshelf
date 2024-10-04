<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\QuotesController as AdminQuotesController;
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
use App\Http\Controllers\PdfController;
use App\Http\Controllers\QuotesController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin'
], function ($router) {
    Route::apiResource('user', UserController::class, [
        'parameters'=> [
            'user'=> 'id'
        ]
    ]);
    Route::group([
        'prefix' => 'user'
    ], function ($router) {
        Route::get('/{id}/books', [UserController::class, 'books']);
        Route::get('/{id}/tickets', [UserController::class, 'tickets']);
        Route::get('/{id}/messages', [UserController::class, 'messages']);
    });

    Route::apiResource('book', AdminBookController::class, [
        'parameters'=> [
            'book'=> 'id'
        ]
    ]);
    Route::group([
        'prefix' => 'book'
    ], function ($router) {
        Route::post('{id}/picture', [AdminBookController::class, 'upload']);
        Route::get('unConfirmed', [AdminBookController::class, 'unConfirmed']);
        Route::put('{id}/verify', [AdminBookController::class, 'verifyBook']);
    });

    Route::apiResource('blog', AdminBlogController::class, [
        'parameters'=> [
            'blog'=> 'id'
        ]
    ]);
    Route::group([
        'prefix' => 'blog'
    ], function ($router) {
        Route::post('{id}/picture', [AdminBlogController::class, 'upload']);
        Route::get('unConfirmed', [AdminBlogController::class, 'unConfirmed']);
        Route::put('{id}/verify', [AdminBlogController::class, 'verifyBlog']);
    });

    Route::apiResource('quote', AdminQuotesController::class, [
        'parameters'=> [
            'quote'=> 'id'
        ]
    ]);
    Route::group([
        'prefix' => 'quote'
    ], function ($router) {
    });

    Route::apiResource('comment', AdminCommentController::class, [
        'parameters'=> [
            'comment'=> 'id'
        ]
    ]);
    Route::group([
        'prefix' => 'comment'
    ], function ($router) {
    });

    Route::group([
        'prefix' => 'ticket'
    ], function ($router) {
        Route::get('', [AdminTicketController::class, 'index']);
        Route::get('open', [AdminTicketController::class, 'open']);
        Route::get('close', [AdminTicketController::class, 'closedTicket']);
        Route::get('{id}', [AdminTicketController::class, 'show']);
        Route::delete('{id}', [AdminTicketController::class, 'destroy']);
        Route::get('/{id}/messages', [AdminTicketController::class, 'messages']);
        Route::get('/{id}/user', [AdminTicketController::class, 'user']);
    });

    Route::group(['prefix'=>'messages'], function () {
        Route::post('{id}', [AdminMessageController::class, 'store']);
        Route::put('{id}', [AdminMessageController::class, 'update']);
        Route::get('{id}', [AdminMessageController::class, 'Messages']);
        Route::delete('{id}', [AdminMessageController::class, 'destroy']);
        Route::get('/{id}/ticket', [AdminMessageController::class, 'ticket']);
        Route::get('/{id}/user', [AdminMessageController::class, 'user']);
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

Route::apiResource('quote', QuotesController::class, [
    'parameters'=> [
        'quote'=> 'id'
    ]
]);
Route::group([
    'prefix' => 'quote'
], function ($router) {
});

Route::get('test', [ExampleController::class, 'test']);
Route::get('test1', [ExampleController::class, 'test1']);
Route::post('contact-us', [ContactUsController::class, 'contact']);
Route::post('pdf', [PdfController::class, 'upload']);
Route::post('pdf/{id}', [PdfController::class, 'upload_file']);
Route::apiResource('comment', CommentController::class, [
    'parameters'=> [
        'comment'=> 'id'
    ]
]);
Route::group([
    'prefix' => 'comment'
], function ($router) {
    Route::get('top', [CommentController::class, 'topComments']);
});

Route::apiResource('ticket', TicketController::class, [
    'parameters'=> [
        'ticket'=> 'id'
    ]
]);
Route::group([
    'prefix' => 'ticket'
], function ($router) {
    Route::get('open', [TicketController::class, 'open']);
    Route::get('close', [TicketController::class, 'closedTicket']);
    Route::post('{id}/close', [TicketController::class, 'closeTicket']);
});

Route::group([
    'prefix' => 'messages'
], function () {
    Route::post('{id}', [MessageController::class, 'store']);
    Route::put('{id}', [MessageController::class, 'update']);
    Route::get('{id}', [MessageController::class, 'Messages']);
    Route::delete('{id}', [MessageController::class, 'destroy']);
});

Route::apiResource('blog', BlogController::class, [
    'parameters'=> [
        'blog'=> 'id'
    ]
]);
Route::group(['prefix'=>'blog'], function () {
});

Route::apiResource('category', CategoryController::class, [
    'parameters'=> [
        'category'=> 'id'
    ]
]);
Route::group(['prefix'=>'category'], function () {
});

Route::apiResource('book', BookController::class, [
    'parameters'=> [
        'book'=> 'id'
    ]
]);
Route::group(['prefix'=>'book'], function () {
});

Route::group([
    'prefix' => 'home'
], function ($router) {
    Route::get('/categories', [HomeController::class, 'categories']);
    Route::get('/book', [HomeController::class, 'book']);
    Route::get('/qoutes', [HomeController::class, 'qoutes']);
});
