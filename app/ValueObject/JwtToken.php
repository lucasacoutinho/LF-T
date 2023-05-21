<?php

namespace App\ValueObject;

class JwtToken
{
    public function __construct(
        private string $token,
        private string $tokenType,
        private int $expiresIn
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
