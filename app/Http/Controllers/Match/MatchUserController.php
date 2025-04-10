<?php

namespace App\Http\Controllers\Match;

use App\API;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
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
     * Undocumented function
     *
     * @return void
     */
    public function matches()
    {
        $user = auth()->user();

        $matches = QueryBuilder::for(User::class)
            ->allowedFilters([
                    AllowedFilter::exact('id'),
                    AllowedFilter::exact('age')
            ])
            ->where('gender', $user->gender === 1 ? 0 : 1)
            ->defaultSort('-created_at')
            ->paginate(20);
        
        return API::success()->response(UserResource::collection($matches));
    }
}
