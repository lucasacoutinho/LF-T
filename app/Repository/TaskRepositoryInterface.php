<?php

namespace App\Repository;

use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get all models by user ID.
     *
     * @param int $id
     * @return LengthAwarePaginator
     */
    public function getByUser(int $id): LengthAwarePaginator;
}
