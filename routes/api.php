<?php

use App\Http\Controllers\Activity\ActivityController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/user')->group(function() {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
});

Route::prefix('/user')->middleware(['auth:sanctum'])->group(function() {

    /************ User Profile Services ***********/
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/', [UserController::class, 'update']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/upload-profile-photo', [UserController::class, 'uploadProfilePhoto']);

    /************* User Post Services ***********/
    Route::prefix('/post')->middleware(['auth:sanctum'])->group(function() {
        Route::post('/', [PostController::class, 'store']);
        Route::get('/', [PostController::class, 'index']);
        Route::post('/like/{postId}', [PostController::class, 'like']);
        Route::post('/favorite/{postId}', [PostController::class, 'favorite']);
        Route::post('/smile/{postId}', [PostController::class, 'smile']);
    });

    /************** Activty User Services ********/
    Route::prefix('/activity')->middleware(['auth:sanctum'])->group(function() {
        Route::post('/like/{userId}', [ActivityController::class, 'like']);
        Route::post('/favorite/{userId}',[ActivityController::class, 'favorite']);
        Route::post('/smile/{userId}',[ActivityController::class, 'smile']);

        Route::get('/liked-profiles', [ActivityController::class, 'likedProfiles']);
        Route::get('/favorite-profiles', [ActivityController::class, 'favoriteProfiles']);

    });
});

