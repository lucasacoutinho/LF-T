<?php

namespace App\Services;

use App\Repository\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaskService
{
    private const CACHE_KEY = 'task_';

    public function __construct(
        private AuthService $authService,
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function all(): LengthAwarePaginator
    {
        return $this->taskRepository->getByUser($this->authService->authenticatedId());
    }

    public function findById(int $id): ?object
    {
        $this->verifyPermission($id);
        return Cache::get(self::CACHE_KEY . $id, fn () => $this->taskRepository->findById($id));
    }

    public function create(array $data): object
    {
        $data = array_merge($data, ['user_id' => $this->authService->authenticatedId()]);
        $task = $this->taskRepository->create($data);
        return Cache::remember(self::CACHE_KEY . $task->getKey(), now()->addHour(), fn () => $task);
    }

    public function update(int $id, array $data): bool
    {
        $this->verifyPermission($id);
        Cache::forget(self::CACHE_KEY . $id);
        return $this->taskRepository->update($id, $data);
    }

    public function deleteById(int $id): bool
    {
        $this->verifyPermission($id);
        Cache::forget(self::CACHE_KEY . $id);
        return $this->taskRepository->deleteById($id);
    }

    private function verifyPermission(int $id): void
    {
        $task = Cache::get(self::CACHE_KEY . $id, fn () => $this->taskRepository->findById($id));

        throw_if(
            $task->user_id !== $this->authService->authenticatedId(),
            new AccessDeniedHttpException('You are not authorized to access this resource.')
        );
    }
}
