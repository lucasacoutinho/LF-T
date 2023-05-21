<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => __('O e-mail do usuário.'),
                'example' => 'usuario@email.com'
            ],
            'password' => [
                'description' => __('A senha do usuário.'),
                'example' => '12345678',
            ],
        ];
    }
}
