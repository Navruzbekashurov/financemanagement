<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GoalResource;

class TransactionResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="Transaction",
     *     type="object",
     *     required={"id","user_id","amount","type"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="user_id", type="integer", example=1),
     *     @OA\Property(property="amount", type="number", format="float", example=50000),
     *     @OA\Property(property="type", type="string", example="income"),
     *     @OA\Property(property="note", type="string", example="Some note"),
     *     @OA\Property(property="date", type="string", format="date", example="2025-11-06"),
     *     @OA\Property(property="category", ref="#/components/schemas/Category"),
     *     @OA\Property(property="entity", type="object")
     * )
     *
     * @OA\Schema(
     *     schema="TransactionCreate",
     *     type="object",
     *     required={"amount","type"},
     *     @OA\Property(property="amount", type="number", format="float", example=50000),
     *     @OA\Property(property="type", type="string", example="income"),
     *     @OA\Property(property="note", type="string", example="Some note"),
     *     @OA\Property(property="date", type="string", format="date", example="2025-11-06"),
     *     @OA\Property(property="category_id", type="integer", example=1),
     *     @OA\Property(property="entity_type", type="string", example="App\\Models\\Goal"),
     *     @OA\Property(property="entity_id", type="integer", example=1)
     * )
     *
     * @OA\Schema(
     *     schema="TransactionUpdate",
     *     type="object",
     *     @OA\Property(property="amount", type="number", format="float", example=50000),
     *     @OA\Property(property="type", type="string", example="income"),
     *     @OA\Property(property="note", type="string", example="Some note"),
     *     @OA\Property(property="date", type="string", format="date", example="2025-11-06"),
     *     @OA\Property(property="category_id", type="integer", example=1),
     *     @OA\Property(property="entity_type", type="string", example="App\\Models\\Goal"),
     *     @OA\Property(property="entity_id", type="integer", example=1)
     * )
     */
    public function toArray($request)
    {
        $entity = $this->whenLoaded('entity');
        $entityData = [];

        if ($entity) {
            $entityData = match ($this->entity_type) {
                'App\\Models\\Goal'     => new GoalResource($entity),
                'App\\Models\\Category' => new CategoryResource($entity),
                default                 => [],
            };
        }

        return [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'amount'    => (float) $this->amount,
            'type'      => $this->type,
            'note'      => $this->note,
            'date'      => $this->date,
            'category'  => new CategoryResource($this->whenLoaded('category')),
            'entity'    => $entityData,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
