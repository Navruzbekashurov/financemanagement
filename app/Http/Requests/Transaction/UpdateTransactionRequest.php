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
            'amount' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:income,expense'],
            'note' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date'],

            // Polymorphic fields
            'entity_id' => ['nullable', 'integer'],
            'entity_type' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $allowedModels = [
                        \App\Models\Goal::class,
                        \App\Models\Debt::class,
                        \App\Models\Category::class,
                        \App\Models\User::class,
                    ];

                    if (!in_array($value, $allowedModels)) {
                        $fail("The selected $attribute is invalid.");
                    }
                }
            ]

        ];
    }
}
