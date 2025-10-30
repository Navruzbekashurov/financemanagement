<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class TransactionResource extends JsonResource
{

    /**
     * @OA\Schema(
     *     schema="TransactionResource",
     *     type="object",
     *     title="Transaction Resource",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="goal_id", type="integer", example=1),
     *     @OA\Property(property="category", type="string", example="Food"),
     *     @OA\Property(property="amount", type="number", format="float", example=125.50),
     *     @OA\Property(property="type", type="string", example="expense"),
     *     @OA\Property(property="note", type="string", example="Lunch at cafe"),
     *     @OA\Property(property="date", type="string", format="date", example="2025-10-29"),
     *     @OA\Property(
     *         property="user",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=2),
     *         @OA\Property(property="name", type="string", example="Ali Valiyev"),
     *         @OA\Property(property="email", type="string", example="ali@example.com")
     *     ),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-29 12:45:30"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-29 12:50:10")
     * )
     */


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'goal_id'=>$this->goal_id,
            'category' => $this->category,
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note,
            'date' => $this->date,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
