<?php

namespace App\DTOs\Category;

use Illuminate\Http\Request;

class UpdateCategoryDto
{
    public function __construct(
        public ?int $user_id,
        public ?string $name = null,
        public ?bool $is_active = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id:$request->user()->id ?? null,
            name: $request->input('name'),
            is_active: $request->has('is_active') ? $request->boolean('is_active') : null
        );
    }
}
