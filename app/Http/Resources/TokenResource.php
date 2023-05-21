<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->getToken(),
            'token_type' => $this->getTokenType(),
            'expires_in' => $this->getExpiresIn(),
        ];
    }
}
