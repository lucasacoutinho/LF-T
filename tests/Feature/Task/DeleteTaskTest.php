<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    private const ROUTE = 'tasks.destroy';

    public function test_route_exists(): void
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_it_expect_user_to_be_authenticated(): void
    {
        $response = $this
            ->getJson(route(self::ROUTE, 0));

        $response
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    public function test_it_expect_task_not_found(): void
    {
        $auth = User::factory()->create();

        $response = $this
            ->actingAs($auth)
            ->getJson(route(self::ROUTE, 0));

        $response->assertNotFound();
    }

    public function test_it_expect_to_be_forbidden_to_delete_other_user_task(): void
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

    public function test_it_expect_to_soft_delete_a_task(): void
    {
        Carbon::setTestNow('2021-01-01 00:00:00');

        $auth = User::factory()->has(Task::factory())->create();
        $task = $auth->tasks->first();

        $response = $this
            ->actingAs($auth)
            ->deleteJson(route(self::ROUTE, $task->getKey()));

        $response->assertNoContent();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->getKey(),
            'deleted_at' => '2021-01-01 00:00:00',
        ]);
    }
}
