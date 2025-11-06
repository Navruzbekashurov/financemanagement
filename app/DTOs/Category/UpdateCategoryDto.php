<?php

namespace App\DTOs\Category;

use Illuminate\Http\Request;

class UpdateCategoryDto
{
    public function __construct(
        public ?string $name = null,
        public ?bool $is_active = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            is_active: $request->has('is_active') ? $request->boolean('is_active') : null
        );
    }
}
