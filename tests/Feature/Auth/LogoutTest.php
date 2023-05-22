<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutTest extends TestCase
{
    private const ROUTE = 'auth.logout';

    public function test_route_exists(): void
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_it_expect_user_to_be_authenticated(): void
    {
        $response = $this
            ->postJson(route(self::ROUTE));

        $response
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_it_can_logout_uthenticated_request(): void
    {
        $auth = User::factory()->create();
        $token = JWTAuth::fromUser($auth);

        $response = $this->withToken($token)->postJson(route(self::ROUTE));

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Successfully logged out'
            ]);
    }
}
