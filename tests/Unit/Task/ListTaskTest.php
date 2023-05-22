<?php

namespace Tests\Unit\Task;

use App\Models\Task;
use App\Models\User;
use App\Repository\TaskRepositoryInterface;
use App\Services\AuthService;
use App\Services\TaskService;
use Illuminate\Pagination\LengthAwarePaginator;
use LengthException;
use Tests\TestCase;

class ListTaskTest extends TestCase
{
    private function getUser(): User
    {
        $user =  new User([
            'name' => 'name',
            'email' => 'email',
            'password' => 'password',
        ]);
        $user->id = 1;

        return $user;
    }

    private function getTask(): object
    {
        $task = new Task([
            'description' => 'description',
            'completed' => false,
            'user_id' => 1,
        ]);
        $task->id = 1;

        return $task;
    }

    public function test_it_expect_to_list_tasks(): void
    {
        $authServiceMock = $this->createMock(AuthService::class);
        $taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $user = $this->getUser();
        $tasks = new LengthAwarePaginator(collect([$this->getTask()]), 1, 1);

        $authServiceMock
            ->expects($this->once())
            ->method('authenticatedId')
            ->willReturn($user->getKey());

        $taskRepositoryMock
            ->expects($this->once())
            ->method('getByUser')
            ->with($user->getKey())
            ->willReturn($tasks);

        $service = new TaskService(
            $authServiceMock,
            $taskRepositoryMock
        );

        $service->all();
    }
}
