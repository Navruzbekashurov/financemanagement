<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DebtResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'creditor'   => $this->creditor,
            'amount'     => (float) $this->amount,
            'due_date'   => $this->due_date,
            'is_active'  => (bool) $this->is_active,
            'category'   => CategoryResource::collection($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
