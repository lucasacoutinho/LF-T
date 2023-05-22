<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutTest extends TestCase
{
    private const ROUTE = 'auth.logout';

    public function test_it_cant_logout_unauthenticated_request(): void
    {
        $response = $this->postJson(route(self::ROUTE));

        $response
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.'
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
