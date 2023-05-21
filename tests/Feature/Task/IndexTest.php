<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private const ROUTE = 'tasks.index';

    public function test_user_without_tasks_returns_empty_result(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route(self::ROUTE));

        $response
            ->assertOk()
            ->assertJson([]);
    }

    public function test_user_with_tasks_returns_result(): void
    {
        $user = User::factory()->has(Task::factory())->create();
        $task = $user->tasks->first();

        $response = $this
            ->actingAs($user)
            ->getJson(route(self::ROUTE));

        $response
            ->assertOk()
            ->assertJson([
                [
                    'id' => $task->getKey(),
                    'description' => $task->description,
                    'completed' => $task->completed,
                    'user_id' => $task->user_id,
                ]
            ]);
    }

    public function test_it_doesnt_show_other_users_tasks(): void
    {
        User::factory()->has(Task::factory(10))->create();
        $auth = User::factory()->has(Task::factory())->create();
        $task = $auth->tasks->first();

        $response = $this
            ->actingAs($auth)
            ->getJson(route(self::ROUTE));

        $response
            ->assertOk()
            ->assertJson([
                [
                    'id' => $task->getKey(),
                    'description' => $task->description,
                    'completed' => $task->completed,
                    'user_id' => $task->user_id,
                ]
            ], true);
    }
}
