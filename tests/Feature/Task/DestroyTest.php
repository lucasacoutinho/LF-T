<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    private const ROUTE = 'tasks.destroy';

    public function test_task_not_found(): void
    {
        $auth = User::factory()->create();

        $response = $this
            ->actingAs($auth)
            ->deleteJson(route(self::ROUTE, 0));

        $response->assertNotFound();
    }

    public function test_forbidden_to_delete_other_user_task(): void
    {
        $user = User::factory()->has(Task::factory())->create();
        $task = $user->tasks->first();
        $auth = User::factory()->create();

        $response = $this
            ->actingAs($auth)
            ->deleteJson(route(self::ROUTE, $task->getKey()));

        $response
            ->assertForbidden()
            ->assertJson(['message' => 'You are not authorized to access this resource.']);
    }

    public function test_it_can_delete_task(): void
    {
        $auth = User::factory()->has(Task::factory())->create();
        $task = $auth->tasks->first();

        $response = $this
            ->actingAs($auth)
            ->deleteJson(route(self::ROUTE, $task->getKey()));

        $response
            ->assertNoContent();
    }
}
