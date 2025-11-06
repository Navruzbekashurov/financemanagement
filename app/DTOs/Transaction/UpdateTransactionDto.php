<?php

namespace App\DTOs\Transaction;

use Illuminate\Http\Request;

class UpdateTransactionDto
{
    public function __construct(
        public ?int    $user_id,
        public ?int    $category_id = null,
        public ?float  $amount = null,
        public ?string $type = null,
        public ?string $note = null,
        public ?string $date = null,
        public ?int    $entity_id = null,
        public ?string $entity_type = null
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id:null,
            category_id: $request->input('category_id'),
            amount: $request->input('amount'),
            type: $request->input('type'),
            note: $request->input('note'),
            date: $request->input('date'),
            entity_id: $request->input('entity_id'),
            entity_type: $request->input('entity_type')
        );
    }
}
