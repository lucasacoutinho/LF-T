<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

/**
 * @group User
 */
class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    /**
     * New User
     *
     * This endpoint create a new user.
     * @responseFile doc/user/user.json
     * @responseFile 422 doc/user/createValidation.json
     */
    public function store(UserStoreRequest $request): UserResource
    {
        return UserResource::make($this->userService->create($request->validated()));
    }
}
