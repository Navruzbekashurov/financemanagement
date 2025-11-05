<?php

namespace App\DTOs\Transaction;

use Illuminate\Http\Request;

class StoreTransactionDto
{
    public function __construct(
        public int $user_id,
        public ?int $category_id,
        public float $amount,
        public string $type,
        public ?string $note,
        public string $date,
        public ?int $entity_id,
        public ?string $entity_type
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id: $request->integer('user_id'),
            category_id: $request->input('category_id'),
            amount: $request->float('amount'),
            type: $request->input('type', 'expense'),
            note: $request->input('note'),
            date: $request->input('date'),
            entity_id: $request->input('entity_id'),
            entity_type: $request->input('entity_type')
        );
    }
}
