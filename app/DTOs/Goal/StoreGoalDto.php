<?php

namespace App\DTOs\Goal;

use Illuminate\Http\Request;

class StoreGoalDto
{
    public function __construct(
        public ?int $user_id,
        public int $category_id,
        public string $title,
        public float $target_amount,
        public ?float $current_amount,
        public string $deadline,
        public bool $is_active
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id: $request->integer('user_id'),
            category_id: $request->integer('category_id', 1),
            title: $request->input('title'),
            target_amount: $request->float('target_amount'),
            current_amount: $request->float('current_amount', 0),
            deadline: $request->input('deadline'),
            is_active: $request->boolean('is_active', true)
        );
    }
}
