<?php

namespace App\DTOs\Debt;

use Illuminate\Http\Request;

class StoreDebtDto
{
    public function __construct(
        public int $user_id,
        public ?int $category_id,
        public string $creditor,
        public float $amount,
        public ?string $due_date,
        public bool $is_active
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id: $request->integer('user_id'),
            category_id: $request->input('category_id'),
            creditor: $request->input('creditor'),
            amount: $request->float('amount'),
            due_date: $request->input('due_date'),
            is_active: $request->boolean('is_active', true)
        );
    }
}
