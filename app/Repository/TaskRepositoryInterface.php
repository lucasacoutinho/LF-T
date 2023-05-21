<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get all models by user ID.
     *
     * @param int $id
     * @return Collection
     */
    public function getByUser(int $id): Collection;
}
