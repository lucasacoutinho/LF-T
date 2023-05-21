<?php

namespace App\Repository\Eloquent;

use App\Models\Task;
use App\Repository\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Collection
     */
    public function getByUser(int $id): Collection
    {
        return $this->model->where('user_id', $id)->get();
    }
}
