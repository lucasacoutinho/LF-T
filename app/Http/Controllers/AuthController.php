<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Authentication
 */
class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private UserService $userService
    ) {
    }

    /**
     * Login
     *
     * This endpoint lets you authenticate.
     * @unauthenticated
     * @responseFile doc/auth/login.json
     * @responseFile 401 doc/auth/loginAuthorization.json
     * @responseFile 422 doc/auth/loginValidation.json
     */
    public function login(LoginRequest $request): TokenResource|JsonResponse
    {
        $token = $this->authService->login($request->validated());

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return TokenResource::make($token);
    }

    /**
     * Logout
     *
     * This endpoint lets you logout.
     * @responseFile doc/auth/logout.json
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh token
     *
     * Return a refresh token.
     * @responseFile doc/auth/login.json
     */
    public function refresh(): TokenResource
    {
        return TokenResource::make($this->authService->refresh());
    }

    /**
     * Authenticated
     *
     * Return a authenticated user.
     * @responseFile doc/auth/authenticated.json
     */
    public function authenticated(): UserResource
    {
        return UserResource::make($this->authService->authenticated());
    }
}
