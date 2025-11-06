<?php

namespace App\DTOs\Debt;

use Illuminate\Http\Request;

class UpdateDebtDto
{
    public function __construct(
        public ?int $user_id,
        public ?string $creditor = null,
        public ?float $amount = null,
        public ?string $due_date = null,
        public ?bool $is_active = null,
        public ?int $category_id = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id:$request->user()->id ?? null,
            creditor: $request->input('creditor'),
            amount: $request->input('amount'),
            due_date: $request->input('due_date'),
            is_active: $request->has('is_active') ? $request->boolean('is_active') : null,
            category_id: $request->input('category_id')
        );
    }
}
