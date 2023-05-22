<?php

namespace Tests\Unit\Task;

use App\Models\User;
use App\Repository\TaskRepositoryInterface;
use App\Services\AuthService;
use App\Services\TaskService;
use Tests\TestCase;

class CreateTaskTest extends TestCase
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

    public function test_it_expect_to_create_a_task(): void
    {
        $authServiceMock = $this->createMock(AuthService::class);
        $taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $user = $this->getUser();
        $data = [
            'description' => 'description',
            'user_id' => $user->getKey(),
        ];

        $authServiceMock
            ->expects($this->once())
            ->method('authenticatedId')
            ->willReturn($user->getKey());

        $taskRepositoryMock
            ->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($user);

        $service = new TaskService(
            $authServiceMock,
            $taskRepositoryMock
        );

        $service->create($data);
    }
}
