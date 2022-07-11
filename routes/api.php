<?php

use App\Http\Controllers\Api\v1\ArticleController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function () {
    Route::get('articles', [ArticleController::class, 'articles']);
    Route::post('comment', [ArticleController::class, 'comment']);
    Route::post('login', [UserController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

    });
});
