<?php

use App\Enums\Role;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\VoteController;
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
    Route::get("/user", [AuthController::class, 'userInformation']);
});

Route::group(['middleware' => ['auth', 'role-check:' . Role::ADMIN], 'prefix' => '/admin'], function () {
    Route::get("/", function () {
        return "You're logging an admin account !!!";
    });
});
Route::group(['middleware' => ['auth', 'role-check:' . Role::USER], 'prefix' => '/user'], function () {
    Route::get("check", function () {
        return "You're logging an user account !!!";
    });
});

Route::group(['middleware' => 'auth', 'prefix' => '/'], function () {

    Route::resource('/blog', BlogController::class)->middleware('auth');
    Route::resource('/category', CategoryController::class);

    Route::post('/vote/{comment}', [VoteController::class, 'makeVote']);
    Route::delete('/vote/{comment}', [VoteController::class, 'removeVote']);

    Route::post('/rate/{blog}', [RateController::class, 'makeRate']);
    Route::delete('/rate/{blog}', [RateController::class, 'removeRate']);

    Route::get('/comments/{blog_id}', [CommentController::class, 'blogComments'])->where('blog_id', '[0-9]+');
    Route::post('/comments/{blog_id}', [CommentController::class, 'postComment'])->where('blog_id', '[0-9]+');

    Route::get('/notifications', [NotificationController::class, 'index'])->where('blog_id', '[0-9]+');
    Route::post('/notifications', [NotificationController::class, 'store'])->where('blog_id', '[0-9]+');
});
