<?php

namespace App\DTOs\Category;

use Illuminate\Http\Request;

class StoreCategoryDto
{
    public function __construct(
        public ?int $user_id,
        public string $name,
        public bool $is_active = true
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            user_id:null,
            name: $request->input('name'),
            is_active: $request->boolean('is_active', true),
        );
    }
}
