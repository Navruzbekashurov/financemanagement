<?php

namespace App\Services;

use App\Models\Debt;
use App\DTOs\Debt\StoreDebtDto;
use App\DTOs\Debt\UpdateDebtDto;

class DebtService
{
    public function create(StoreDebtDto $dto): Debt
    {
        return Debt::create([
            'user_id' => $dto->user_id,
            'creditor' => $dto->creditor,
            'amount' => $dto->amount,
            'due_date' => $dto->due_date,
            'is_active' => $dto->is_active ?? true,
        ]);
    }

    public function update(Debt $debt, UpdateDebtDto $dto): Debt
    {
        $debt->update([
            'creditor' => $dto->creditor,
            'amount' => $dto->amount,
            'due_date' => $dto->due_date,
            'is_active' => $dto->is_active,
        ]);

        return $debt;
    }
}
