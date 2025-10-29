<?php

namespace App\Services;

use App\DTOs\Transaction\StoreTransactionDto;
use App\DTOs\Transaction\UpdateTransactionDto;
use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    public function store(StoreTransactionDto $dto, User $user): Transaction
    {
        $data = $dto->toArray();
        $data['user_id'] = $user->id;

        return Transaction::create($data);
    }

    public function update(Transaction $transaction, UpdateTransactionDto $dto): Transaction
    {
        $transaction->update($dto->toArray());
        return $transaction->fresh();
    }

}
