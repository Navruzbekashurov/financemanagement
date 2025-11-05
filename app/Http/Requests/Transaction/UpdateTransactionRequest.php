<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'type' => ['sometimes', 'in:income,expense'],
            'note' => ['sometimes', 'string', 'max:255'],
            'date' => ['sometimes', 'date'],
            'entity_id' => ['nullable', 'integer'],
            'entity_type' => ['nullable', 'string', 'in:App\Models\Goal,App\Models\Debet'],
        ];
    }
}
