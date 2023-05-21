<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    private const ROUTE = 'tasks.update';

    public function test_task_not_found(): void
    {
        $auth = User::factory()->create();

        $response = $this
            ->actingAs($auth)
            ->putJson(route(self::ROUTE, 0));

        $response->assertNotFound();
    }

    public function test_forbidden_to_update_other_user_task(): void
    {
        $user = User::factory()->has(Task::factory())->create();
        $task = $user->tasks->first();
        $auth = User::factory()->create();
        $payload = Task::factory()->make()->toArray();

        $response = $this
            ->actingAs($auth)
            ->putJson(route(self::ROUTE, $task->getKey()), $payload);

        $response
            ->assertForbidden()
            ->assertJson(['message' => 'You are not authorized to access this resource.']);
    }

    public function test_it_can_update_task(): void
    {
        $auth = User::factory()->has(Task::factory())->create();
        $task = $auth->tasks->first();
        $payload = Task::factory()->make()->toArray();

        $response = $this
            ->actingAs($auth)
            ->putJson(route(self::ROUTE, $task->getKey()), $payload);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Task updated.',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->getKey(),
            'description' => $payload['description'],
            'completed' => $payload['completed'],
            'user_id' => $task->user_id,
        ]);
    }
}
