<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private const ROUTE = 'user.store';

    public function test_required_fields(): void
    {
        $auth = User::factory()->create();

        $response = $this
            ->actingAs($auth)
            ->postJson(route(self::ROUTE));

        $response
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function test_fields_type_failed(): void
    {
        $auth = User::factory()->create();
        $payload = [
            'name' => ['A'],
            'email' => ['A'],
            'password' => ['A'],
        ];

        $response = $this
            ->actingAs($auth)
            ->postJson(route(self::ROUTE), $payload);

        $response
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'name' => ['The name field must be a string.'],
                    'email' => ['The email field must be a string.'],
                    'password' => ['The password field must be a string.']
                ]
            ]);
    }

    public function test_it_cant_create_a_user_with_same_email(): void
    {
        $user = User::factory()->create();
        $payload = User::factory()->make()->toArray();
        $payload['email'] = $user->email;

        $response = $this->postJson(route(self::ROUTE), $payload);

        $response
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'email' => ['The email has already been taken.']
                ]
            ]);
    }

    public function test_it_can_create_a_user(): void
    {
        $payload = User::factory()->make()->toArray();
        $payload['password'] = 'password';

        $response = $this->postJson(route(self::ROUTE), $payload);

        $response
            ->assertOk()
            ->assertJson([
                'name'  => $payload['name'],
                'email' => $payload['email'],
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => $payload['name'],
            'email' => $payload['email'],
        ]);
    }
}
