<?php

namespace App\Repository\Eloquent;

use App\Models\Task;
use App\Repository\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /**
     * Get all models by user ID.
     *
     * @param int $id
     * @return LengthAwarePaginator
     */
    public function getByUser(int $id): LengthAwarePaginator
    {
        return $this->model->where('user_id', $id)->paginate();
    }
}
