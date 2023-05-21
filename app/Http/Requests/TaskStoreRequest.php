<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'description' => [
                'description' => __('A descrição da tarefa.'),
            ],
        ];
    }
}
