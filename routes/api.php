<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('register', [AuthController::class,'register']);
    Route::get('refresh', [AuthController::class,'refresh']);
    Route::get('me', [AuthController::class,'me']);
});


Route::group([
    'prefix' => 'book'
], function ($router) {
    Route::get('', [BookController::class,'index']);
    Route::post('', [BookController::class,'store']);
    Route::get('{id}', [BookController::class,'show']);
    Route::put('{id}', [BookController::class,'update']);
    Route::delete('{id}', [BookController::class,'destroy']);
});
