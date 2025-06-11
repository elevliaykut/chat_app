<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Activity\ActivityController;
use App\Http\Controllers\Definition\DefinitionController;
use App\Http\Controllers\Match\MatchUserController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Story\StoryController;
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

Route::get('/cities', [DefinitionController::class, 'cities']);
Route::get('/districts/{cityId}', [DefinitionController::class, 'districts']);

Route::prefix('/user')->middleware(['auth:sanctum'])->group(function() {

    /************ User Profile Services ***********/
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/', [UserController::class, 'update']);
    Route::put('/personal-information', [UserController::class, 'personalInformation']);
    Route::put('/spouse-candidate', [UserController::class, 'spouseCandidate']);
    Route::put('/caracteristic-feature', [UserController::class, 'caracteristicFeature']);
    Route::post('/photo', [UserController::class, 'storePhoto']);
    Route::get('/photo/{userId}', [UserController::class, 'listPhoto']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/upload-profile-photo', [UserController::class, 'uploadProfilePhoto']);

    /************* My Posts *********************/
    Route::get('/my-posts', [UserController::class, 'myPosts']);
    Route::get('/my-blocked-users', [UserController::class, 'myBlockedUsers']);

    /************* User Message Services *********/
    Route::post('/messages/send', [MessageController::class, 'sendMessage']);
    Route::get('/messages/{userId}', [MessageController::class, 'getMessages']);
    Route::get('/incoming-message-logs', [MessageController::class, 'getIncomingMessageLogs']);
    Route::get('/outgoing-message-logs', [MessageController::class, 'getOutgoingMessageLogs']);
    Route::delete('/incoiming-message/{senderId}', [MessageController::class, 'deleteIncomingMessage']);
    Route::delete('/outgoing-message/{receiverId}', [MessageController::class, 'deleteOutGoingMessage']);
    Route::post('/read-incoming-message/{senderId}', [MessageController::class, 'readIncomingMessage']);

    /************* User Block & Report Activity **********/
    Route::post('/blocked/{userId}', [UserController::class, 'blockedUser']);
    Route::post('/unblock/{userId}', [UserController::class, 'unBlockedUser']);
    Route::post('/report/{userId}', [UserController::class, 'reportUser']);

    /************ Account Services  *******/
    Route::prefix('/account')->middleware(['auth:sanctum'])->group(function() {
        Route::post('/freeze', [AccountController::class, 'freeze']);
        Route::delete('/delete', [AccountController::class, 'delete']);
        Route::post('/change-email', [AccountController::class, 'changeEmail']);
    });

    /****************** Matches User Services *****************/
    Route::prefix('/match')->middleware(['auth:sanctum'])->group(function() {
        Route::get('/', [MatchUserController::class, 'matches']);
        Route::get('/previus', [MatchUserController::class, 'matchPrevius']);
    });

    /************* User Post Services ***********/
    Route::prefix('/post')->middleware(['auth:sanctum'])->group(function() {
        Route::post('/', [PostController::class, 'store']);
        Route::get('/', [PostController::class, 'index']);
        Route::post('/like/{postId}', [PostController::class, 'like']);
        Route::post('/favorite/{postId}', [PostController::class, 'favorite']);
        Route::post('/smile/{postId}', [PostController::class, 'smile']);

        /********** Activities */
        Route::get('/liked-posts', [PostController::class, 'likedPosts']);
        Route::get('/favorite-posts', [PostController::class, 'favoritePosts']);
        Route::get('/smiled-posts', [PostController::class, 'smiledPosts']);
    });

    /************** Activty User Services ********/
    Route::prefix('/activity')->middleware(['auth:sanctum'])->group(function() {
        Route::post('/like/{userId}', [ActivityController::class, 'like']);
        Route::post('/favorite/{userId}',[ActivityController::class, 'favorite']);
        Route::post('/smile/{userId}',[ActivityController::class, 'smile']);

        Route::get('/liked-profiles', [ActivityController::class, 'likedProfiles']);
        Route::get('/favorite-profiles', [ActivityController::class, 'favoriteProfiles']);
        Route::get('/similed-profiles', [ActivityController::class, 'similedProfiles']);
        Route::get('/online-users', [ActivityController::class, 'getOnlineUsers']);
        
        // ALL USER LIST WITH FILTER
        Route::get('/filter', [ActivityController::class, 'filter']);
    });

    Route::prefix('/profile/visit')->group(function() {
        Route::post('/{userId}', [UserController::class, 'createUserProfileVisitLog']);
        Route::get('/', [UserController::class, 'getUserProfileVisit']);
    });

    Route::prefix('/story')->group(function() {
        Route::post('/', [StoryController::class, 'store']);
        Route::get('/', [StoryController::class, 'index']);
    });
});

Route::prefix('/member')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/detail/{memberId}', [MemberController::class, 'getMemberDetails']);
    Route::get('/posts/{memberId}', [MemberController::class, 'getMemberPosts']);
});

Route::prefix('/notification')->middleware(['auth:sanctum'])->group(function() {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/read-all', [NotificationController::class, 'readAll']);
    Route::delete('/{id}', [NotificationController::class, 'delete']);
});
