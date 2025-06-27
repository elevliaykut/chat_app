<?php

namespace App\Http\Controllers\Member;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostListResource;
use App\Http\Resources\User\UserResource;
use App\Models\Post\Post;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MemberController extends Controller
{
    protected UserService $userService;

        /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService                  = $userService;
    }

    /**
     * @param memberId
     * @return JsonResponse
     */
    public function getMemberDetails(int $memberId): JsonResponse
    {
        $member = $this->userService->retrieveById($memberId);

        return API::success()->response(UserResource::make($member));
    }

    /**
     * @param memberId
     * @return JsonResponse
     */
    public function getMemberPosts(int $memberId): JsonResponse
    {
        $currentUserId = auth()->user()->id;

        $postsQuery = QueryBuilder::for(Post::class)
            ->where('creator_user_id', $memberId)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('creator_user_id'),
                AllowedFilter::exact('status')
            ])
            ->defaultSort('-created_at');
        
        if ($currentUserId !== $memberId) {
            $postsQuery->where('status', 1);
        }

        $posts = $postsQuery->get();
        

        return API::success()->response(PostListResource::collection($posts));
    }
}
