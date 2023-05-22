<?php

namespace Tests\Unit\Task;

use App\Models\Task;
use App\Models\User;
use App\Repository\TaskRepositoryInterface;
use App\Services\AuthService;
use App\Services\TaskService;
use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;

class DetailTaskTest extends TestCase
{
    private function getUser(): object
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

    public function test_it_expect_to_throw_an_exception_when_another_user_tries_to_see(): void
    {
        $authServiceMock = $this->createMock(AuthService::class);
        $taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $task = $this->getTask();
        $diffUserId = 2;

        Cache::shouldReceive('get')
            ->once()
            ->with(TaskService::CACHE_KEY . $task->getKey(), Closure::class)
            ->andReturn($task);

        $authServiceMock
            ->expects($this->once())
            ->method('authenticatedId')
            ->willReturn($diffUserId);

        $this->expectExceptionObject(
            new AccessDeniedHttpException('You are not authorized to access this resource.')
        );

        $service = new TaskService(
            $authServiceMock,
            $taskRepositoryMock
        );

        $service->findById($task->getKey());
    }

    public function test_it_expect_to_show_a_task(): void
    {
        $authServiceMock = $this->createMock(AuthService::class);
        $taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $user = $this->getUser();
        $task = $this->getTask();

        Cache::shouldReceive('get')
            ->once()
            ->with(TaskService::CACHE_KEY . $task->getKey(), Closure::class)
            ->andReturn($task);

        $authServiceMock
            ->expects($this->once())
            ->method('authenticatedId')
            ->willReturn($user->getKey());

        Cache::shouldReceive('get')
            ->once()
            ->with(TaskService::CACHE_KEY . $task->getKey(), Closure::class)
            ->andReturn($task);

        $service = new TaskService(
            $authServiceMock,
            $taskRepositoryMock
        );

        $service->findById($task->getKey());
    }
}
