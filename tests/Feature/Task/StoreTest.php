<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class StoreTest extends TestCase
{
    private const ROUTE = 'tasks.store';

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
