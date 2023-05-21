<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private const ROUTE = 'tasks.show';

    public function test_task_not_found(): void
    {
        $auth = User::factory()->create();

        $response = $this
            ->actingAs($auth)
            ->getJson(route(self::ROUTE, 0));

        $response->assertNotFound();
    }

    public function test_forbidden_to_see_other_user_task(): void
    {
        $user = User::factory()->has(Task::factory())->create();
        $task = $user->tasks->first();
        $auth = User::factory()->create();

        $response = $this
            ->actingAs($auth)
            ->getJson(route(self::ROUTE, $task->getKey()));

        $response
            ->assertForbidden()
            ->assertJson(['message' => 'You are not authorized to access this resource.']);
    }

    public function test_it_can_see_user_task(): void
    {
        $auth = User::factory()->has(Task::factory())->create();
        $task = $auth->tasks->first();

        $response = $this
            ->actingAs($auth)
            ->getJson(route(self::ROUTE, $task->getKey()));

        $response
            ->assertOk()
            ->assertJson(
                [
                    'id' => $task->getKey(),
                    'description' => $task->description,
                    'completed' => $task->completed,
                    'user_id' => $task->user_id,
                ]
            );
    }
}
