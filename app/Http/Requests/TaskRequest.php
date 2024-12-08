<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'nullable|in:pending,completed'
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->due_date) {
            $this->merge([
                'due_date' => date('Y-m-d H:i:s', strtotime($this->due_date))
            ]);
        }
    }
}
