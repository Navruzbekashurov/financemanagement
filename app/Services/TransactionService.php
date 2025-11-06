<?php

namespace App\Services;

use App\Models\Transaction;
use App\DTOs\Transaction\StoreTransactionDto;
use App\DTOs\Transaction\UpdateTransactionDto;

class TransactionService
{
    public function create(StoreTransactionDto $dto): Transaction
    {
        $transaction = new Transaction([
            'user_id' => $dto->user_id,
            'amount' => $dto->amount,
            'type' => $dto->type,
            'note' => $dto->note,
            'date' => $dto->date,
        ]);

        // Polimorfik entity bog'lash
        if ($dto->entity_id && $dto->entity_type) {
            $entityClass = $dto->entity_type; // App\Models\Goal yoki App\Models\Category
            $entity = $entityClass::find($dto->entity_id);

            if ($entity) {
                $transaction->entity()->associate($entity);
            }
        }

        $transaction->save();

        return $transaction;
    }

    public function update(Transaction $transaction, UpdateTransactionDto $dto): Transaction
    {
        $transaction->fill([
            'amount' => $dto->amount,
            'type' => $dto->type,
            'note' => $dto->note,
            'date' => $dto->date,
        ]);

        // Polimorfik entity bog'lash
        if (!empty($dto->entity_id) && !empty($dto->entity_type)) {
            $entityClass = $dto->entity_type; // App\Models\Goal yoki App\Models\Category
            $entity = $entityClass::find($dto->entity_id);

            if ($entity) {
                $transaction->entity()->associate($entity);
            } else {
                // Entity topilmasa, bog'lanishni olib tashlash
                $transaction->entity()->dissociate();
            }
        } elseif ($dto->entity_id === null && $dto->entity_type === null) {
            // DTOda entity bo'lmasa, bog'lanishni olib tashlash
            $transaction->entity()->dissociate();
        }

        $transaction->save();

        return $transaction;
    }
}
