<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTest extends TestCase
{
    private const ROUTE = 'auth.refresh';

    public function test_it_cant_logout_unauthenticated_request(): void
    {
        $response = $this->postJson(route(self::ROUTE));

        $response
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_it_can_generate_refresh_token(): void
    {
        $auth = User::factory()->create();
        $token = JWTAuth::fromUser($auth);

        $response = $this->withToken($token)->postJson(route(self::ROUTE));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }
}
