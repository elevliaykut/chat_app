<?php

namespace App\Http\Controllers\Post;

use App\API;
use App\Helper\Types\PostActivityTypeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\Post\PostListResource;
use App\Models\Post\Post;
use App\Services\Post\PostActivityLogService;
use App\Services\Post\PostPhotoService;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    protected PostService $postService;

    protected PostPhotoService $postPhotoService;
    protected PostActivityLogService $postActivityLogService;

    protected int $defaultPerPage = 20;

    /**
     * PostController constructor.
     * @param PostService $postService
     * @param PostPhotoService $postPhotoService
     */
    public function __construct(PostService $postService, PostPhotoService $postPhotoService, PostActivityLogService $postActivityLogService)
    {
        $this->postService = $postService;
        $this->postPhotoService = $postPhotoService;
        $this->postActivityLogService = $postActivityLogService;
    }

    /**
     * @param CreatePostRequest $createPostRequest
     * @return JsonResponse
     */
    public function store(CreatePostRequest $createPostRequest): JsonResponse
    {
        $validatedData = $createPostRequest->validated();

        $validatedData['creator_user_id'] = auth()->user()->id;
        $validatedData['status'] = 1;

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
    public function index(Request $request): JsonResponse
    {
        $posts = QueryBuilder::for(Post::class)
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
    public function like(int $postId): JsonResponse
    {   
        if($this->postActivityLogService->check($postId, auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_LIKE)) {
            return API::error()->errorMessage('Beğendiğiniz Bir Gönderiyi Tekrar Beğenemezsiniz!')->response();
        }

        $post = $this->postService->retrieveById($postId);
        $post = $this->postService->likePost($post);

        $activityLogData = [
            'post_id'           => $post->id,
            'activity_user_id'  => auth()->user()->id,
            'activity_type'     => PostActivityTypeHelper::POST_ACTIVITY_TYPE_LIKE
        ];

        $this->postActivityLogService->create($activityLogData);

        return API::success()->response();
    }

    /**
     * @param int $postId
     * @return JsonResponse
     */
    public function favorite(int $postId): JsonResponse
    {
        if($this->postActivityLogService->check($postId, auth()->user()->id, PostActivityTypeHelper::POST_ACTIVITY_TYPE_MAKE_FAVORITE)) {
            return API::error()->errorMessage('Favorilere Eklediğiniz Bir Gönderiyi Tekrar Ekleyemezsiniz!')->response();
        }

        $activityLogData = [
            'post_id'               => $postId,
            'activity_user_id'      => auth()->user()->id,
            'activity_type'         => PostActivityTypeHelper::POST_ACTIVITY_TYPE_MAKE_FAVORITE
        ];

        $this->postActivityLogService->create($activityLogData);

        return API::success()->response();
    }
}
