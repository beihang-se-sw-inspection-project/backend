<?php

use App\Http\Controllers\AuthorPostRelatedController;
use App\Http\Controllers\AuthorPostRelationshipController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentPostRelatedController;
use App\Http\Controllers\CommentPostRelationshipController;
use App\Http\Controllers\CommentUserRelatedController;
use App\Http\Controllers\CommentUserRelationshipController;
use App\Http\Controllers\CurrentAuthenticatedUserController;
use App\Http\Controllers\PostAuthorRelatedController;
use App\Http\Controllers\PostAuthorRelationshipController;
use App\Http\Controllers\PostCommentRelatedController;
use App\Http\Controllers\PostCommentRelationshipController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\UserCommentRelatedController;
use App\Http\Controllers\UserCommentRelationshipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\APIAuthenticationController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\APIForgotPasswordController;
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
    Route::post('noauth/users', [UserController::class,'store']);
    Route::post('noauth/auth', [APIAuthenticationController::class,'store']);

    //handling password 
    Route::apiResource('noauth/passwordresetcode', APIForgotPasswordController::class)->missing(function () {
    return response(['errors' => ['title'=> 'Not found', 'detail' => 'Invalid or expired code.']], 404);
});
});

/**
 * Authenticated api routes
 */
Route::middleware('auth:api')->prefix('v1')->group(function () {

    // Users
    Route::get('users/me', [APIAuthenticationController::class, 'show']);
    Route::delete('auth/logout', [APIAuthenticationController::class,'destroy']);
    
    Route::apiResource('users', UserController::class);

    Route::get('users/{user}/relationships/comments', [UserCommentRelationshipController::class, 'index'])->name('users.relationships.comments');
    Route::patch('users/{user}/relationships/comments', [UserCommentRelationshipController::class, 'update'])->name('users.relationships.comments');
    Route::get('users/{user}/comments', [UserCommentRelatedController::class, 'index'])->name('users.comments');

    Route::get('users/{user}/relationships/posts', [AuthorPostRelationshipController::class, 'index'])->name('users.relationships.posts');
    Route::patch('users/{user}/relationships/posts', [AuthorPostRelationshipController::class, 'update'])->name('users.relationships.posts');
    Route::get('users/{user}/posts', [AuthorPostRelatedController::class, 'index'])->name('users.posts');

    // Posts
    Route::apiResource('posts', PostController::class);

    Route::get('posts/{post}/relationships/users', [PostAuthorRelationshipController::class, 'index'])->name('posts.relationships.users');
    Route::patch('posts/{post}/relationships/users', [PostAuthorRelationshipController::class, 'update'])->name('posts.relationships.users');
    Route::get('posts/{post}/users', [PostAuthorRelatedController::class, 'index'])->name('posts.users');

    Route::get('posts/{post}/relationships/comments', [PostCommentRelationshipController::class, 'index'])->name('posts.relationships.comments');
    Route::patch('posts/{post}/relationships/comments', [PostCommentRelationshipController::class, 'update'])->name('posts.relationships.comments');
    Route::get('posts/{post}/comments', [PostCommentRelatedController::class, 'index'])->name('posts.comments');

    // Comments
    Route::apiResource('comments', CommentController::class);

    Route::get('comments/{comment}/relationships/users', [CommentUserRelationshipController::class, 'index'])->name('comments.relationships.users');
    Route::patch('comments/{comment}/relationships/users', [CommentUserRelationshipController::class, 'update'])->name('comments.relationships.users');
    Route::get('comments/{comment}/users', [CommentUserRelatedController::class, 'index'])->name('comments.users');

    Route::get('comments/{comment}/relationships/posts', [CommentPostRelationshipController::class, 'index'])->name('comments.relationships.posts');
    Route::patch('comments/{comment}/relationships/posts', [CommentPostRelationshipController::class, 'update'])->name('comments.relationships.posts');
    Route::get('comments/{comment}/posts', [CommentPostRelatedController::class, 'index'])->name('comments.posts');

    // Post likes
    Route::apiResource('postlikes', PostLikeController::class);

    // files
    Route::apiResource('files', FileController::class);
});
