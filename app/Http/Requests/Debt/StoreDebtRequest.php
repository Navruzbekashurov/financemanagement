<?php

namespace App\Http\Requests\Debt;

use Illuminate\Foundation\Http\FormRequest;

class StoreDebtRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['nullable|exists:categories,id'],
            'creditor'   => ['required|string|max:255'],
            'amount'     => ['required|numeric'],
            'due_date'   => ['nullable|date'],
            'is_active'  => ['boolean'],
        ];
    }
}
