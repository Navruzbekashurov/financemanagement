<?php

namespace App\DTOs\Goal;

use Illuminate\Http\Request;

class StoreGoalDto
{
    public function __construct(
        public string $title,
        public float $target_amount,
        public float $current_amount,
        public string $deadline,
        public ?bool $is_active = true
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('title'),
            $request->input('target_amount'),
            $request->input('current_amount'),
            $request->input('deadline'),
            $request->input('is_active', true)
        );
    }

//    public function toArray(): array
//    {
//        return [
//            'title'          => $this->title,
//            'target_amount'  => $this->target_amount,
//            'current_amount' => $this->current_amount,
//            'deadline'       => $this->deadline,
//            'is_active'      => $this->is_active,
//        ];
//    }
}
