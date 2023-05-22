<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ListTaskTest extends TestCase
{
    private const ROUTE = 'tasks.index';

    public function test_route_exists(): void
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_it_expect_user_to_be_authenticated(): void
    {
        $response = $this
            ->getJson(route(self::ROUTE));

        $response
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_user_without_tasks_returns_empty_result(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route(self::ROUTE));

        $response
            ->assertOk()
            ->assertJson([
                'data' => []
            ]);
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
                'data' => [
                    [
                        'id' => $task->getKey(),
                        'description' => $task->description,
                        'completed' => $task->completed,
                        'user_id' => $task->user_id,
                    ]
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
                'data' => [
                    [
                        'id' => $task->getKey(),
                        'description' => $task->description,
                        'completed' => $task->completed,
                        'user_id' => $task->user_id,
                    ]
                ]
            ]);
    }
}
