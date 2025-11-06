<?php

namespace App\Http\Requests\Debt;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'exists:categories,id'],
            'creditor' => ['sometimes', 'string', 'max:255'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'due_date' => ['sometimes', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
