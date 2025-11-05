<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'category'        => new CategoryResource($this->whenLoaded('category')),
            'title'           => $this->title,
            'target_amount'   => (float) $this->target_amount,
            'current_amount'  => (float) $this->current_amount,
            'deadline'        => $this->deadline,
            'is_active'       => (bool) $this->is_active,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
