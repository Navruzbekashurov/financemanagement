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
            'category_id'=>$dto->category_id,
            'user_id' => $dto->user_id,
            'creditor' => $dto->creditor,
            'amount' => $dto->amount,
            'due_date' => $dto->due_date,
            'is_active' => $dto->is_active ?? true,
        ]);
    }

    public function update(Debt $debt, UpdateDebtDto $dto): Debt
    {
        $data = [
            'creditor'    => $dto->creditor ?? $debt->creditor,
            'amount'      => $dto->amount ?? $debt->amount,
            'due_date'    => $dto->due_date ?? $debt->due_date,
            'is_active'   => $dto->is_active ?? $debt->is_active,
            'category_id' => $dto->category_id ?? $debt->category_id,
        ];

        // update boolean qaytaradi
        $debt->update($data);

        // har doim Debt obyektini return qilamiz
        return Debt::findOrFail($debt->id);
    }

}
