<?php

use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
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

Route::post("/login", [AuthController::class, 'login']);
Route::get("/logout", [AuthController::class, 'logout']);


Route::middleware("auth")->group(function () {
    Route::get("/user",[AuthController::class, 'userInformation']);
});

Route::group(['middleware' => ['auth', 'role-check:' . \App\Enums\Role::ADMIN], 'prefix' => '/admin'], function () {
    Route::get("/", function () {
        return "You're logging an admin account !!!";
    });
});
Route::group(['middleware' => ['auth', 'role-check:' . \App\Enums\Role::USER], 'prefix' => '/user'], function () {
    Route::get("check", function () {
        return "You're logging an user account !!!";
    });
});

Route::resource('/blog', BlogController::class)->middleware('auth');
Route::resource('/category', \App\Http\Controllers\CategoryController::class)->middleware('auth');
Route::post('/vote/{comment}', [\App\Http\Controllers\VoteController::class, 'makeVote'])->middleware('auth');
Route::delete('/vote/{comment}', [\App\Http\Controllers\VoteController::class, 'removeVote'])->middleware('auth');Route::post('/vote/{comment}', [\App\Http\Controllers\VoteController::class, 'makeVote'])->middleware('auth');
Route::post('/rate/{blog}', [\App\Http\Controllers\RateController::class, 'makeRate'])->middleware('auth');
Route::delete('/rate/{blog}', [\App\Http\Controllers\RateController::class, 'removeRate'])->middleware('auth');

Route::get('/comments/{blog_id}', [CommentController::class ,'blogComments'])->where('blog_id', '[0-9]+');;
Route::post('/comments/{blog_id}', [CommentController::class ,'postComment'])->where('blog_id', '[0-9]+');
