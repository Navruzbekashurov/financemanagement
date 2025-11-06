<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="DebtResource",
 *     type="object",
 *     title="Debt",
 *     required={"id","user_id","creditor","amount","due_date"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="creditor", type="string", example="Ali aka"),
 *     @OA\Property(property="amount", type="number", format="float", example=500000),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-11-30"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="DebtCreate",
 *     type="object",
 *     required={"creditor","amount","due_date"},
 *     @OA\Property(property="creditor", type="string", example="Ali aka"),
 *     @OA\Property(property="amount", type="number", format="float", example=500000),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-11-30"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="category_id", type="integer", example=3)
 * )
 *
 * @OA\Schema(
 *     schema="DebtUpdate",
 *     type="object",
 *     @OA\Property(property="creditor", type="string", example="Ali aka"),
 *     @OA\Property(property="amount", type="number", format="float", example=500000),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-11-30"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="category_id", type="integer", example=3)
 * )
 */

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
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
