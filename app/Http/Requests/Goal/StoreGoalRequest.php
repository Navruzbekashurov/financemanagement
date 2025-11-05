<?php

namespace App\Http\Requests\Goal;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category_id'    => ['nullable', 'integer', 'exists:categories,id'],
            'title'          => ['required', 'string', 'max:255'],
            'target_amount'  => ['required', 'numeric', 'min:0'],
            'current_amount' => ['required', 'numeric', 'min:0'],
            'deadline'       => ['required', 'date', 'after_or_equal:today'],
            'is_active'      => ['boolean'],
        ];
    }
}

