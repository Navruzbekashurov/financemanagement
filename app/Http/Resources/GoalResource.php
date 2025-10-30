<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="GoalResource",
 *     type="object",
 *     title="Goal Resource",
 *     description="User goal (financial target) representation",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Uy uchun jamg‘arma"),
 *     @OA\Property(property="target_amount", type="number", format="float", example=10000.00),
 *     @OA\Property(property="current_amount", type="number", format="float", example=2500.00),
 *     @OA\Property(property="deadline", type="string", format="date", example="2025-12-31"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=3),
 *         @OA\Property(property="name", type="string", example="Navruzbek Ashurov"),
 *         @OA\Property(property="email", type="string", example="navruz@example.com")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-30 14:25:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-30 15:10:00")
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
            'id'             => $this->id,
            'title'          => $this->title,
            'target_amount'  => $this->target_amount,
            'current_amount' => $this->current_amount,
            'deadline'       => $this->deadline,
            'is_active'      => (bool) $this->is_active,
            'user' => [
                'id'    => $this->user?->id,
                'name'  => $this->user?->name,
                'email' => $this->user?->email,
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
