<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private const ROUTE = 'auth.login';

    public function test_required_fields(): void
    {
        $response = $this->postJson(route(self::ROUTE));

        $response
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function test_fields_type_failed(): void
    {
        $payload = [
            'email' => ['A'],
            'password' => ['A'],
        ];

        $response = $this->postJson(route(self::ROUTE), $payload);

        $response
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'email' => ['The email field must be a string.'],
                    'password' => ['The password field must be a string.']
                ]
            ]);
    }

    public function test_it_authenticate_inexistent_user(): void
    {
        $payload = User::factory()->make()->only(['email', 'password']);

        $response = $this->postJson(route(self::ROUTE), $payload);

        $response
            ->assertUnauthorized()
            ->assertJson([
                'error' => 'Unauthorized'
            ]);
    }

    public function test_it_authenticate_user(): void
    {
        $payload = User::factory()->create()->only(['email']);
        $payload['password'] = 'password';

        $response = $this->postJson(route(self::ROUTE), $payload);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }
}
