<?php

namespace App\Http\Controllers\Match;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
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
            ->where('gender', $user->gender === 1 ? 0 : 1)
            ->inRandomOrder()
            ->first();

        return API::success()->response(UserResource::make($matches));
    }
}
