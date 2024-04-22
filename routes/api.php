<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});

Route::group([

  //  'middleware' => 'api',
    'prefix' => 'book'

], function ($router) {

    Route::get('', [BookController::class,'index']);
    Route::post('', [BookController::class,'store']);
    Route::get('{id}', [BookController::class,'show']);
    Route::put('{id}', [BookController::class,'update']);
    Route::delete('{id}', [BookController::class,'destroy']);

});