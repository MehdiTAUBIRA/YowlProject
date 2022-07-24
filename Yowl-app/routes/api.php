<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\postsController;
use App\Http\Controllers\Api\commentsController;
use App\Http\Controllers\Api\usersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/posts', [postsController::class,'store']);

Route::apiResource('posts', postsController::class);
Route::apiResource('comments', commentsController::class);
Route::apiResource('users', usersController::class);
//Route::apiResource('/login', [usersController::class,'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});