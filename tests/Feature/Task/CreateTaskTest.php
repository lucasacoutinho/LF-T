<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    private const ROUTE = 'tasks.store';

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
                    'description' => ['The description field is required.']
                ]
            ]);
    }

    public function test_fields_type_failed(): void
    {
        $auth = User::factory()->create();
        $payload = ['description' => ['A']];

        $response = $this
            ->actingAs($auth)
            ->postJson(route(self::ROUTE), $payload);

        $response
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'description' => ['The description field must be a string.']
                ]
            ]);
    }

    public function test_it_can_create_task(): void
    {
        $auth = User::factory()->create();
        $payload = Task::factory()->make()->toArray();

        $response = $this
            ->actingAs($auth)
            ->postJson(route(self::ROUTE), $payload);

        $response
            ->assertOk()
            ->assertJson([
                'description' => $payload['description'],
                'completed' => false,
                'user_id' => $auth->getKey(),
            ]);

        $this->assertDatabaseHas('tasks', [
            'description' => $payload['description'],
            'completed' => false,
            'user_id' => $auth->getKey(),
        ]);
    }
}
