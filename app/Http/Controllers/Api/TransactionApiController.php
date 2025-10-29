<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use App\DTOs\Transaction\StoreTransactionDto;
use App\DTOs\Transaction\UpdateTransactionDto;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionApiController extends Controller
{
    public function __construct(protected TransactionService $transactionService) {}

    /**
     * Display a list of authenticated user's transactions.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return TransactionResource::collection($transactions);
    }

    /**
     * Show a single transaction if owned by the authenticated user.
     */
    public function show(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return new TransactionResource($transaction);
    }

    /**
     * Create a new transaction for the authenticated user.
     */
    public function store(TransactionRequest $request)
    {
        $dto = StoreTransactionDto::fromRequest($request);
        $transaction = $this->transactionService->store($dto, $request->user());

        return response()->json([
            'message' => 'Transaction created successfully!',
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    /**
     * Update user's own transaction.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $dto = UpdateTransactionDto::fromRequest($request);
        $transaction = $this->transactionService->update($transaction, $dto);

        return response()->json([
            'message' => 'Transaction updated successfully!',
            'data' => new TransactionResource($transaction),
        ]);
    }

    /**
     * Delete user's own transaction.
     */
    public function destroy(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully!']);
    }
}
