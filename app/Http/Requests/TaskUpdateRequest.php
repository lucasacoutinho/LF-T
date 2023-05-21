<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['filled', 'string'],
            'completed' => ['filled', 'boolean'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'description' => [
                'description' => __('A descrição da tarefa.'),
            ],
            'completed' => [
                'description' => __('O status da tarefa.'),
                'example' => true
            ],
        ];
    }
}
