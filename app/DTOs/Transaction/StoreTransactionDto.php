<?php

namespace App\DTOs\Transaction;

use App\Http\Requests\Transaction\TransactionRequest;

class StoreTransactionDto
{
    public function __construct(
        public int $user_id,
        public string $category,
        public float $amount,
        public string $type,
        public ?string $note,
        public string $date,
    ) {}

    public static function fromRequest(TransactionRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            $validated['user_id'],
            $validated['category'],
            $validated['amount'],
            $validated['type'],
            $validated['note'] ?? null,
            $validated['date'],
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'category' => $this->category,
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note,
            'date' => $this->date,
        ];
    }
}
