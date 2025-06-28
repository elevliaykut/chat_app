<?php

namespace App\Http\Controllers\Post;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Helper\Types\PostActivityTypeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\Activity\ActivityPostResource;
use App\Http\Resources\Post\PostListResource;
use App\Models\Post\Post;
use App\Models\Post\PostActivityLog;
use App\Services\Notification\NotificationService;
use App\Services\Post\PostActivityLogService;
use App\Services\Post\PostPhotoService;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    protected PostService $postService;

    protected PostPhotoService $postPhotoService;
    protected PostActivityLogService $postActivityLogService;
    protected NotificationService $notificationService;

    protected int $defaultPerPage = 20;

    /**
     * PostController constructor.
     * @param PostService $postService
     * @param PostPhotoService $postPhotoService
     */
    public function __construct(
        PostService $postService, 
        PostPhotoService $postPhotoService, 
        PostActivityLogService $postActivityLogService,
        NotificationService $notificationService
    )
    {
        $this->postService = $postService;
        $this->postPhotoService = $postPhotoService;
        $this->postActivityLogService = $postActivityLogService;
        $this->notificationService = $notificationService;
    }

    /**
     * @param CreatePostRequest $createPostRequest
     * @return JsonResponse
     */
    public function store(CreatePostRequest $createPostRequest): JsonResponse
    {
        $validatedData = $createPostRequest->validated();

        $validatedData['creator_user_id'] = auth()->user()->id;
        $validatedData['status'] = 0;

        $post = $this->postService->create($validatedData);

        if($createPostRequest->file('photo')) {
            $file = $createPostRequest->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads', $fileName, 'public');
            $imageUrl = asset('storage/uploads/' . $fileName);
            
            $postPhotoData = [
                'post_id'       => $post->id,
                'photo_path'    => $imageUrl
            ];

            $this->postPhotoService->create($postPhotoData);
        }

        return API::success()->response();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $currentGender = auth()->user()->gender;
        $currentUserId = auth()->user()->id;

        $posts = QueryBuilder::for(Post::class)
            ->where('status', 1)
            ->whereHas('creatorUser', function ($query) use ($currentGender) {
                $query->where('gender', '!=', $currentGender);
            })
            ->whereHas('creatorUser', function ($query) use ($currentGender) {
                $query->where('status', UserStatusHelper::USER_STATUS_ACTIVE);
            })
            ->whereDoesntHave('creatorUser.blockers', function ($query) use ($currentUserId) {
                $query->where('blocker_id', $currentUserId);
            })
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('creator_user_id'),
                AllowedFilter::exact('status')
            ])
            ->defaultSort('-created_at')
            ->paginate($this->defaultPerPage);

        return API::success()->response(PostListResource::collection($posts));
    }

    /**
     * @param int $postId
     * @return JsonResponse
     */
    public function like(Request $request, int $postId)
    {   
        $post = $this->postService->retrieveById($postId);

        if($request->input('status') == 1) {
            
            if($this->postActivityLogService->check($postId, auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_LIKE)) {
                return API::error()->errorMessage('Beğendiğiniz Bir Gönderiyi Tekrar Beğenemezsiniz!')->response();
            }
            
            $this->postService->likePost($post);
    
            $activityLogData = [
                'post_id'           => $postId,
                'activity_user_id'  => auth()->user()->id,
                'activity_type'     => PostActivityTypeHelper::POST_ACTIVITY_TYPE_LIKE
            ];
    
            $this->postActivityLogService->create($activityLogData);
    
            $notifData = [
                'user_id'               => $post->creator_user_id,
                'notified_user_id'      => auth()->user()->id,
                'message'               => 'Paylaşımızı Beğendi'
            ];
    
            $this->notificationService->create($notifData);
    
            return API::success()->response();
        }

        if($request->input('status') == 0) {

            $this->postService->unLikePost($post);

            PostActivityLog::where('post_id', $postId)
                ->where('activity_user_id', auth()->user()->id)
                ->where('activity_type', PostActivityTypeHelper::POST_ACTIVITY_TYPE_LIKE)
                ->delete();

            return API::success()->response();
        }
    }

    /**
     * @param int $postId
     * @return JsonResponse
     */
    public function favorite(Request $request, int $postId)
    {
        $post = $this->postService->retrieveById($postId);

        if($request->input('status') == 1) {
            if($this->postActivityLogService->check($postId, auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_MAKE_FAVORITE)) {
                return API::error()->errorMessage('Favorilere Eklediğiniz Bir Gönderiyi Tekrar Ekleyemezsiniz!')->response();
            }
            
            $this->postService->favoritePost($post);
    
            $activityLogData = [
                'post_id'               => $postId,
                'activity_user_id'      => auth()->user()->id,
                'activity_type'         => PostActivityTypeHelper::POST_ACTIVITY_TYPE_MAKE_FAVORITE
            ];
    
            $this->postActivityLogService->create($activityLogData);
    
            $notifData = [
                'user_id'               => $post->creator_user_id,
                'notified_user_id'      => auth()->user()->id,
                'message'               => 'Paylaşımızı Favorilere Ekledi'
            ];
    
            $this->notificationService->create($notifData);
    
            return API::success()->response();
        }

        if($request->input('status') == 0) {
            
            $this->postService->unFavoritePost($post);
            
            PostActivityLog::where('post_id', $postId)
                ->where('activity_user_id', auth()->user()->id)
                ->where('activity_type', PostActivityTypeHelper::POST_ACTIVITY_TYPE_MAKE_FAVORITE)
                ->delete();

            return API::success()->response();
        }
    }

    /**
     * @param int $postId
     * @return JsonResponse
     */
    public function smile(Request $request, int $postId)
    {
        $post = $this->postService->retrieveById($postId);

        if($request->input('status') == 1) {
            
            if($this->postActivityLogService->check($postId, auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_SMILE)) {
                return API::error()->errorMessage('İfade Bıraktığınız Bir Gönderiye Tekrar İfade Bırakamazsınız!')->response();
            }
    
            $this->postService->smilePost($post);
    
            $activityLogData = [
                'post_id'               => $postId,
                'activity_user_id'      => auth()->user()->id,
                'activity_type'         => PostActivityTypeHelper::POST_ACTIVITY_TYPE_SMILE
            ];
    
            $this->postActivityLogService->create($activityLogData);
    
            $notifData = [
                'user_id'               => $post->creator_user_id,
                'notified_user_id'      => auth()->user()->id,
                'message'               => 'Paylaşımıza Gülümsedi'
            ];
    
            $this->notificationService->create($notifData);
    
            return API::success()->response();
        }

        if($request->input('status') == 0) {
            
            $this->postService->unSmilePost($post);
            
            PostActivityLog::where('post_id', $postId)
                ->where('activity_user_id', auth()->user()->id)
                ->where('activity_type', PostActivityTypeHelper::POST_ACTIVITY_TYPE_SMILE)
                ->delete();

            return API::success()->response();
        }
    }

    /**
     * @return JsonResponse
     */
    public function likedPosts(): JsonResponse
    {
        $postActivityLogs = $this->postActivityLogService->getByActivityUserAndType(auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_LIKE);

        return API::success()->response(ActivityPostResource::collection($postActivityLogs));
    }

    /**
     * @return JsonResponse
     */
    public function favoritePosts(): JsonResponse
    {
        $postActivityLogs = $this->postActivityLogService->getByActivityUserAndType(auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_MAKE_FAVORITE);

        return API::success()->response(ActivityPostResource::collection($postActivityLogs));
    }

    /**
     * @return JsonResponse
     */
    public function smiledPosts(): JsonResponse
    {
        $postActivityLogs = $this->postActivityLogService->getByActivityUserAndType(auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_SMILE);

        return API::success()->response(ActivityPostResource::collection($postActivityLogs));
    }
}
