<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Task management
 */
class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService,
    ) {
    }

    /**
     * Tasks
     *
     * This endpoint return the user tasks.
     * @responseFile doc/task/tasks.json
     */
    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->taskService->all());
    }

    /**
     * Find Task
     *
     * This endpoint return a task.
     * @urlParam id required The ID of the task.
     * @responseFile doc/task/task.json
     * @response 403 {"message": "You are not authorized to access this resource."}
     */
    public function show(int $id): TaskResource
    {
        return TaskResource::make($this->taskService->findById($id));
    }

    /**
     * New Task
     *
     * This endpoint create a new task.
     * @responseFile doc/task/task.json
     * @responseFile 422 doc/task/createValidation.json
     */
    public function store(TaskStoreRequest $request): TaskResource
    {
        return TaskResource::make($this->taskService->create($request->validated()));
    }

    /**
     * Update a Task
     *
     * This endpoint update a task.
     * @urlParam id required The ID of the task.
     * @response {"message": "Task updated."}
     * @response 403 {"message": "You are not authorized to access this resource."}
     * @responseFile 422 doc/task/updateValidation.json
     */
    public function update(int $id, TaskUpdateRequest $request): JsonResponse
    {
        $this->taskService->update($id, $request->validated());
        return response()->json(['message' => 'Task updated.']);
    }

    /**
     * Delete a Task
     *
     * This endpoint delete a task.
     * @urlParam id required The ID of the task.
     * @response 204 {"message": "Task deleted."}
     * @response 403 {"message": "You are not authorized to access this resource."}
     */
    public function destroy(int $id): JsonResponse
    {
        $this->taskService->deleteById($id);

        return response()->json(['message' => 'Task deleted.'], Response::HTTP_NO_CONTENT);
    }
}
