<?php

namespace App\Services;

use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function create(array $data): object
    {
        $data = array_merge($data, ['password' => Hash::make($data['password'])]);
        return $this->userRepository->create($data);
    }
}
