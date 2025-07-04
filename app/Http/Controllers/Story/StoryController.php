<?php

namespace App\Http\Controllers\Story;

use App\API;
use App\Helper\Statuses\UserStatusHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserStoryRequest;
use App\Http\Resources\User\UserStoryResource;
use App\Models\User;
use App\Services\User\StoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    protected StoryService $storyService;

    /**
     * StoryController constructor.
     * @param StoryService $storyService
     */
    public function __construct(StoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    /**
     * @param CreateUserStoryRequest $createUserStoryRequest
     * @return JsonResponse
     */
    public function store(CreateUserStoryRequest $createUserStoryRequest): JsonResponse 
    {
        $file = $createUserStoryRequest->file('media');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('stories', $fileName, 'public');
        $imageUrl = asset('storage/stories/' . $fileName);

        $data = [
            'user_id'       => auth()->user()->id,
            'media_path'    => $imageUrl,
            'expires_at'    => now()->addHours(24),
        ];

        $story = $this->storyService->create($data);

        return API::success()->response(UserStoryResource::make($story));
    }

    /**
     * @return JsonResponse
     */
    public function index(Request $request)
    {        
        $currentUserId = auth()->user()->id;
        $oppositeGender = auth()->user()->gender === 1 ? 0 : 1;

        $users = User::where('id', '!=', $currentUserId)
            ->where('status', UserStatusHelper::USER_STATUS_ACTIVE)
            ->where('gender', $oppositeGender)
            ->whereHas('stories', function ($query) {
                $query->where('expires_at', '>', now());
            })
            ->with(['stories' => function ($query) {
                $query->where('expires_at', '>', now())
                    ->where('status', 1)
                    ->orderBy('created_at', 'asc');
            }])
            ->get();

        $data = $users->filter(function ($user) {
            return $user->stories->isNotEmpty();
        })->values();

        if ($data->isNotEmpty()) {
            return response()->json([
                'data' => $data->map(function ($user) {
                    return [
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'profile_photo_url' => $user->photo_approve === 1 ? $user->profile_photo_path : null,
                        'stories' => $user->stories->map(function ($story) {
                            return [
                                'id' => $story->id,
                                'media_url' => $story->media_path,
                                'created_at' => $story->created_at,
                                'expires_at' => $story->expires_at,
                            ];
                        })
                    ];
                })
            ]);
        }

        // Hiç story yoksa boş response dön
        return response()->json(['data' => []]);

    }

    /**
     * @return JsonResponse
     */
    public function myStory(Request $request)
    {
        $currentUserId = auth()->user()->id;

        $user = User::where('id', $currentUserId)
            ->with(['stories' => function ($query) {
                $query->where('expires_at', '>', now())
                    ->orderBy('created_at', 'asc');
            }])
            ->first();

        // Eğer kullanıcı yoksa boş dizi dön
        if (!$user) {
            return response()->json(['data' => []]);
        }

        // Aktif story'leri filtrele
        $activeStories = $user->stories->filter(function ($story) {
            return $story->expires_at > now();
        })->values();

        return response()->json([
            'data' => [
                [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'profile_photo_url' => $user->profile_photo_path,
                    'stories' => $activeStories->map(function ($story) {
                        return [
                            'id' => $story->id,
                            'media_url' => $story->media_path,
                            'created_at' => $story->created_at,
                            'expires_at' => $story->expires_at,
                            'status' => $story->status,
                        ];
                    }),
                ]
            ]
        ]);
    }


}
