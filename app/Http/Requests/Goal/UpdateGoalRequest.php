<?php

namespace App\Http\Requests\Goal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'exists:categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'target_amount' => ['sometimes', 'numeric', 'min:0'],
            'current_amount' => ['sometimes', 'numeric', 'min:0'],
            'deadline' => ['sometimes', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
