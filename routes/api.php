<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/register",[AuthController::class,'register']);
Route::post("/login",[AuthController::class,'login']);

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post("/logout",[AuthController::class,'logout']);
    Route::get("/my_posts",[PostController::class,'my_posts']);
    Route::resource('post', PostController::class);
    Route::resource('comment', CommentController::class);
});



