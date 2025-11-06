<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\DTOs\Transaction\StoreTransactionDto;
use App\DTOs\Transaction\UpdateTransactionDto;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactionService) {}

    public function index(): JsonResponse
    {
        return response()->json(Transaction::with('entity')->latest()->get());
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $dto = StoreTransactionDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $transaction = $this->transactionService->create($dto);
        return response()->json($transaction, 201);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return response()->json($transaction->load('entity'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $dto = UpdateTransactionDto::fromRequest($request);
        $updated = $this->transactionService->update($transaction, $dto);
        return response()->json($updated);
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
