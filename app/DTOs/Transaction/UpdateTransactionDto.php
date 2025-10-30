<?php

namespace App\DTOs\Transaction;

use App\Http\Requests\Transaction\UpdateTransactionRequest;

class UpdateTransactionDto
{
    public function __construct(
        public ?int $goal_id,
        public string $category,
        public float $amount,
        public string $type,
        public ?string $note,
        public string $date,
    ) {}

    public static function fromRequest(UpdateTransactionRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            $validated['goal_id']?? null,
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
            'category' => $this->category,
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note,
            'date' => $this->date,
        ];
    }
}
