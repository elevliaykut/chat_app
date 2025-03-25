<?php

namespace App\Http\Controllers\Post;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Services\Post\PostPhotoService;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostController extends Controller
{
    protected PostService $postService;

    protected PostPhotoService $postPhotoService;

    /**
     * PostController constructor.
     * @param PostService $postService
     * @param PostPhotoService $postPhotoService
     */
    public function __construct(PostService $postService, PostPhotoService $postPhotoService)
    {
        $this->postService = $postService;
        $this->postPhotoService = $postPhotoService;
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
}
