<?php

namespace App\Services;

use App\Models\Goal;
use App\DTOs\Goal\StoreGoalDto;
use App\DTOs\Goal\UpdateGoalDto;

class GoalService
{
    public function create(StoreGoalDto $dto): Goal
    {
        return Goal::create([
            'user_id' => $dto->user_id,
            'category_id' => $dto->category_id,
            'title' => $dto->title,
            'target_amount' => $dto->target_amount,
            'current_amount' => $dto->current_amount ?? 0,
            'deadline' => $dto->deadline,
            'is_active' => $dto->is_active ?? true,
        ]);
    }

    public function update(Goal $goal, UpdateGoalDto $dto): Goal
    {
        $goal->update([
            'category_id' => $dto->category_id ?? $goal->category_id, 'title' => $dto->title,
            'target_amount' => $dto->target_amount,
            'current_amount' => $dto->current_amount,
            'deadline' => $dto->deadline,
            'is_active' => $dto->is_active ?? $goal->is_active,
        ]);

        return $goal;
    }
}
