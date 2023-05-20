<?php

namespace App\Services;

use App\Repository\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }
}
