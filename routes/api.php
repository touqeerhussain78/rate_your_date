<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'user'], function () {
        //Auth
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('signup', [AuthController::class, 'register'])->name('signup');
        Route::post('forgot-password', [AuthController::class, 'sendForgotCode'])->name('forgot-password');
        Route::post('verify-code', [AuthController::class, 'verifiedForgotCode'])->name('verifiedForgotCode');
        Route::post('update-password', [AuthController::class, 'forgotPasswordChange'])->name('update-password');

        Route::post('contact-us', [AuthController::class, 'contactUs'])->name('contact-us');

        Route::group(['middleware' => 'auth:api'], function () {

            // Profile
            Route::get('profile', [AuthController::class, 'getProfile'])->name('get-profile');
            Route::post('update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');

            // Password Update & Logout
            Route::post('change-password', [AuthController::class, 'changePassword'])->name('changePassword');
            Route::post('logout', [AuthController::class, 'logoutUser'])->name('logoutUser');


            // User Posts, Comments, Likes
            Route::get('newsFeeds', [App\Http\Controllers\Api\User\PostController::class, 'getNewsFeedPosts'])->name('newsFeeds');
            Route::get('postDetail/{id}', [App\Http\Controllers\Api\User\PostController::class, 'getPostDetail'])->name('postDetail');
            Route::post('createPost', [App\Http\Controllers\Api\User\PostController::class, 'createPost'])->name('createPost');
            Route::post('updatePost/{post}', [App\Http\Controllers\Api\User\PostController::class, 'updatePost'])->name('updatePost');
            Route::get('getLikes/{post}', [App\Http\Controllers\Api\User\PostController::class, 'getLikes'])->name('getLikes');
            Route::get('likePost/{post}', [App\Http\Controllers\Api\User\PostController::class, 'likePost'])->name('likePost');
            Route::get('unlikePost/{post}', [App\Http\Controllers\Api\User\PostController::class, 'unlikePost'])->name('unlikePost');
            Route::post('deletePost/{post}', [App\Http\Controllers\Api\User\PostController::class, 'deletePost'])->name('deletePost');
            Route::get('/getComments/{post}', [App\Http\Controllers\Api\User\PostController::class, 'getComments'])->name('getComments');
            Route::post('/addComment/{post}', [App\Http\Controllers\Api\User\PostController::class, 'saveComment'])->name('addComment');
            Route::post('/deleteComment/{id}', [App\Http\Controllers\Api\User\PostController::class, 'deletePostComment'])->name('deleteComment');
            Route::post('/updateComment/{id}', [App\Http\Controllers\Api\User\PostController::class, 'updatePostComment'])->name('updateComment');

            // Notifications
            Route::get('/getNotifications', [App\Http\Controllers\Api\User\NotificationController::class, 'index'])->name('getNotifications');

        });
    });
});
