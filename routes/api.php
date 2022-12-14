<?php

use App\Http\Controllers\APIAuthenticationController;
use App\Http\Controllers\APIForgotPasswordController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

/**
 * home url
 */
Route::get('/', function () {
    return json_encode([
        "about" => "Software Inspection App API version 1",
    ]);
});

/**
 * Non authenticated api routes
 */
Route::prefix('v1')->group(function () {
    // user authentication routes
    Route::post('noauth/users', [UserController::class, 'store']);
    Route::post('noauth/auth', [APIAuthenticationController::class, 'store']);

    //handling password
    Route::apiResource('noauth/passwordresetcode', APIForgotPasswordController::class)->missing(function () {
        return response(['errors' => ['title' => 'Not found', 'detail' => 'Invalid or expired code.']], 404);
    });
});

/**
 * Authenticated api routes
 */
Route::middleware('auth:api')->prefix('v1')->group(function () {

    // Users
    Route::get('users/me', [APIAuthenticationController::class, 'show']);
    Route::delete('auth/logout', [APIAuthenticationController::class, 'destroy']);
    Route::apiResource('users', UserController::class);

    // projects
    Route::apiResource('projects', ProjectController::class);

    // tasks
    Route::apiResource('tasks', TaskController::class);

    // inspections
    Route::apiResource('inspections', InspectionController::class);
});
