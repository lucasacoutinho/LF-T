<?php

namespace App\Services;

use App\Models\User;
use App\ValueObject\JwtToken;

class AuthService
{
    public function login(array $credentials): JwtToken|false
    {
        $token = auth()->attempt($credentials);

        if (!$token) {
            return false;
        }

        return $this->buildToken($token);
    }

    public function refresh(): JwtToken
    {
        return $this->buildToken(auth()->refresh());
    }

    private function buildToken(string $token): JwtToken
    {
        return new JwtToken(
            $token,
            'bearer',
            auth()->factory()->getTTL() * 60
        );
    }

    public function logout(): void
    {
        auth()->logout();
    }

    public function authenticated(): User
    {
        return auth()->user();
    }
}
