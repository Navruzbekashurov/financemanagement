<?php

namespace App\DTOs\Category;

use Illuminate\Http\Request;

class UpdateCategoryDto
{
    public function __construct(
        public ?string $name = null,
        public ?string $type = null,
        public ?bool $is_active = null
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            type: $request->input('type'),
            is_active: $request->has('is_active') ? $request->boolean('is_active') : null
        );
    }
}
