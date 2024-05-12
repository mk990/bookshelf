<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('change-password',[AuthController::class,'changePassword']);
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

Route::get('test', [ExampleController::class, 'test']);

//Route::post('change-password',[AuthController::class,'changePassword']);