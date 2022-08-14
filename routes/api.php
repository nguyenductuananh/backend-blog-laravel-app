<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    Route::get("/user", function () {
        return auth()->user();
    });
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