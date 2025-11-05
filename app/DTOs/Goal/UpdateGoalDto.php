<?php

namespace App\DTOs\Goal;

use Illuminate\Http\Request;

class UpdateGoalDto
{
    public function __construct(
        public ?int $category_id = null,
        public ?string $title = null,
        public ?float $target_amount = null,
        public ?float $current_amount = null,
        public ?string $deadline = null,
        public ?bool $is_active = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            category_id: $request->input('category_id'),
            title: $request->input('title'),
            target_amount: $request->input('target_amount'),
            current_amount: $request->input('current_amount'),
            deadline: $request->input('deadline'),
            is_active: $request->has('is_active') ? $request->boolean('is_active') : null
        );
    }
}
