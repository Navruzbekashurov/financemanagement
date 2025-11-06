<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="GoalResource",
 *     type="object",
 *     title="Goal",
 *     required={"id","user_id","title","target_amount"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category"
 *     ),
 *     @OA\Property(property="title", type="string", example="Umra uchun tejash!"),
 *     @OA\Property(property="target_amount", type="number", format="float", example=15000000),
 *     @OA\Property(property="current_amount", type="number", format="float", example=250000),
 *     @OA\Property(property="deadline", type="string", format="date", example="2025-12-31"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="GoalCreate",
 *     type="object",
 *     required={"title","target_amount","deadline"},
 *     @OA\Property(property="title", type="string", example="Umra uchun tejash!"),
 *     @OA\Property(property="target_amount", type="number", format="float", example=15000000),
 *     @OA\Property(property="current_amount", type="number", format="float", example=0),
 *     @OA\Property(property="deadline", type="string", format="date", example="2025-12-31"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="category_id", type="integer", example=1)
 * )
 *
 * @OA\Schema(
 *     schema="GoalUpdate",
 *     type="object",
 *     @OA\Property(property="title", type="string", example="Umra uchun tejash!"),
 *     @OA\Property(property="target_amount", type="number", format="float", example=15000000),
 *     @OA\Property(property="current_amount", type="number", format="float", example=250000),
 *     @OA\Property(property="deadline", type="string", format="date", example="2025-12-31"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="category_id", type="integer", example=1)
 * )
 */
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
