<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        $entity = $this->whenLoaded('entity');
        $entityData = [];

        if ($entity) {
            // Agar entity polimorfik bo‘lsa — har xil modelni JSON ga o‘tkazamiz
            $entityData = match ($this->entity_type) {
                'App\\Models\\Goal'     => new GoalResource($entity),
                'App\\Models\\Category' => new CategoryResource($entity),
                default                 => [],
            };
        }

        $data = [
            'id'        => $this->id,
            'user_id'   => $this->user_id,
            'amount'    => $this->amount,
            'type'      => $this->type,
            'note'      => $this->note,
            'date'      => $this->date,
        ];

        // Agar entity mavjud bo‘lsa, asosiy dataga birlashtiramiz
        return array_merge($data, [
            'entity'   => $entityData,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ]);
    }

}
