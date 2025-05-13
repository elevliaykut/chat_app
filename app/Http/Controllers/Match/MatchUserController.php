<?php

namespace App\Http\Controllers\Match;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\Match\MatchHistory;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MatchUserController extends Controller
{
    protected UserService $userService;

    /**
     * Undocumented function
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return JsonResponse
     */
    public function matches(): JsonResponse
    {
        $user = auth()->user();

        $matches = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('creator_user_id'),
                AllowedFilter::exact('status'),
                AllowedFilter::scope('near_users'),
                AllowedFilter::scope('born_today_date'),
                AllowedFilter::exact('gender'),
                AllowedFilter::scope('starts_between'),
                AllowedFilter::scope('username'),
                AllowedFilter::scope('min_age_range'),
                AllowedFilter::scope('max_age_range'),
                AllowedFilter::scope('min_tall'),
                AllowedFilter::scope('max_tall'),
                AllowedFilter::scope('min_weight'),
                AllowedFilter::scope('max_weight'),
                AllowedFilter::scope('city_id'),
                AllowedFilter::scope('job'),
                AllowedFilter::scope('marital_status'),
                AllowedFilter::scope('have_a_child'),
                AllowedFilter::scope('use_cigarette'),
                AllowedFilter::scope('use_alcohol'),
                AllowedFilter::scope('education'),
                AllowedFilter::scope('salary'),
                AllowedFilter::scope('physical'),
                AllowedFilter::scope('physical'),
                AllowedFilter::scope('has_photos'),
                AllowedFilter::scope('head_craft'),
            ])  
            ->where('id', '!=', auth()->user()->id)
            ->where('liked_by_me', false)
            ->where('gender', $user->gender === 1 ? 0 : 1)
            ->inRandomOrder()
            ->first();
        
        MatchHistory::create([
            'shown_user_id'         => $matches->id,
            'activity_user_id'      => auth()->user()->id
        ]);

        return API::success()->response(UserResource::make($matches));
    }

    /**
     * @return JsonResponse
     */
    public function matchPrevius(): JsonResponse
    {
        $match = auth()->user()->matchHistories()
            ->latest()
            ->take(2)
            ->get();

        $log = $match->count() >= 2 ? $match->get(1) : null;

        return API::success()->response(UserResource::make($log->user));
    }
}
