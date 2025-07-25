<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Activity\ActivityController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Definition\DefinitionController;
use App\Http\Controllers\Match\MatchUserController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Payment\PaymentController;
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
    Route::delete('/photo/{photoId}', [UserController::class, 'deletePhoto']);
    
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/upload-profile-photo', [UserController::class, 'uploadProfilePhoto']);
    Route::get('/photos', [UserController::class, 'photos']);

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
        Route::post('/freeze/{status}', [AccountController::class, 'freeze']);
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
        Route::get('/approve', [ActivityController::class, 'getApproveUsers']);
    });

    Route::prefix('/profile/visit')->group(function() {
        Route::post('/{userId}', [UserController::class, 'createUserProfileVisitLog']);
        Route::get('/', [UserController::class, 'getUserProfileVisit']);
    });

    Route::prefix('/story')->group(function() {
        Route::post('/', [StoryController::class, 'store']);
        Route::get('/', [StoryController::class, 'index']);
        Route::get('/me', [StoryController::class, 'myStory']);
    });

    Route::prefix('/payment')->group(function() {
        Route::post('/', [PaymentController::class, 'store']);
        Route::post('/complete/{id}', [PaymentController::class, 'paymentComplete']);
        Route::get('/', [PaymentController::class, 'index']);
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

Route::prefix('/admin')->group(function() {
    // login
    Route::post('/login', [AdminController::class, 'login']);
});

Route::prefix('/admin')->middleware(['auth:sanctum'])->group(function() {
    // USER SERVICE
    Route::get('/user', [AdminController::class, 'getUsers']);
    Route::get('/user/{userId}', [AdminController::class, 'getUserDetail']);
    Route::delete('/user/{userId}', [AdminController::class, 'deleteUser']);

    // STORY SERVICE
    Route::get('/user/stories/list', [AdminController::class, 'stories']);
    Route::post('/user/approve/story/{storyId}', [AdminController::class, 'approveStory']);

    // POST SERVICE
    Route::get('/user/posts/list', [AdminController::class, 'posts']);
    Route::post('/user/approve/post/{postId}', [AdminController::class, 'approvePost']);

    //PHOTOS SERVICE
    Route::get('/user/photos/list', [AdminController::class, 'photos']);
    Route::post('/user/approve/photo/{photoId}', [AdminController::class, 'approvePhoto']);

    // profile text service
    Route::get('/user/profile/text/list', [AdminController::class, 'profileTextList']);
    Route::post('/user/profile/text/approve/{detailId}', [AdminController::class, 'profileTextApprove']);

    // payments services
    Route::get('/user/payment/list', [AdminController::class, 'paymentList']);
    Route::post('/user/payment/approve/{paymentId}', [AdminController::class, 'approvePayment']);

    // reports service
    Route::get('/user/reports/list', [AdminController::class, 'reportsList']);
    Route::delete('/user/report/{reportId}', [AdminController::class, 'deleteReport']);

    // profile photos
    Route::get('/user/profile-photos/list', [AdminController::class, 'profilePhotoList']);
    Route::post('/user/profile-photo/approve/{userId}', [AdminController::class, 'approveProfilePhoto']);
});
