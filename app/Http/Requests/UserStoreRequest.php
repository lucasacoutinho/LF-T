<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => __('O nome do usuário.'),
            ],
            'email' => [
                'description' => __('O email do usuário.'),
            ],
            'password' => [
                'description' => __('A senha do usuário.'),
            ],
        ];
    }
}
