<?php

namespace App\Services;

use App\DTOs\Goal\StoreGoalDto;
use App\Models\Goal;
use App\Models\User;

class GoalService
{
    /**
     * Yangi maqsad yaratish
     */
    public function store(StoreGoalDto $dto, User $user): Goal
    {
        return Goal::create([
            'user_id'        => $user->id,
            'title'          => $dto->title,
            'target_amount'  => $dto->target_amount,
            'current_amount' => $dto->current_amount,
            'deadline'       => $dto->deadline,
            'is_active'      => $dto->is_active ?? true,
        ]);
    }

    /**
     * Maqsadni yangilash
     */
    public function update(Goal $goal, StoreGoalDto $dto): Goal
    {
        $goal->update([
            'title'          => $dto->title,
            'target_amount'  => $dto->target_amount,
            'current_amount' => $dto->current_amount,
            'deadline'       => $dto->deadline,
            'is_active'      => $dto->is_active ?? $goal->is_active,
        ]);

        return $goal;
    }
}
