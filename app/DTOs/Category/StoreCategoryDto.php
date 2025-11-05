<?php

namespace App\DTOs\Category;

use Illuminate\Http\Request;

class StoreCategoryDto
{
    public function __construct(
        public string $name,
        public string $type,
        public bool $is_active = true
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            type: $request->input('type', 'expense'),
            is_active: $request->boolean('is_active', true)
        );
    }
}
