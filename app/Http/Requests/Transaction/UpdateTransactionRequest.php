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
            'goal_id' => 'nullable|exists:goals,id',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:expense,income',
            'note' => 'nullable|string|max:500',
            'date' => 'required|date',
        ];
    }
}
