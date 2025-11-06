<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\DTOs\Transaction\StoreTransactionDto;
use App\DTOs\Transaction\UpdateTransactionDto;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactionService) {}

    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="Get all transactions",
     *     tags={"Transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="List of transactions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Transaction")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(TransactionResource::collection(Transaction::with('entity', 'category')->latest()->get()));
    }

    /**
     * @OA\Post(
     *     path="/api/transactions",
     *     summary="Create a new transaction",
     *     tags={"Transactions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     )
     * )
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $dto = StoreTransactionDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $transaction = $this->transactionService->create($dto);
        return response()->json(new TransactionResource($transaction->load('entity', 'category')), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/transactions/{transaction}",
     *     summary="Get a single transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="transaction",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction details",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     ),
     *     @OA\Response(response=404, description="Transaction not found")
     * )
     */
    public function show(Transaction $transaction): JsonResponse
    {
        return response()->json(new TransactionResource($transaction->load('entity', 'category')));
    }

    /**
     * @OA\Put(
     *     path="/api/transactions/{transaction}",
     *     summary="Update a transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="transaction",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated transaction",
     *         @OA\JsonContent(ref="#/components/schemas/Transaction")
     *     )
     * )
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $dto = UpdateTransactionDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $updated = $this->transactionService->update($transaction, $dto);
        return response()->json(new TransactionResource($updated->load('entity', 'category')));
    }

    /**
     * @OA\Delete(
     *     path="/api/transactions/{transaction}",
     *     summary="Delete a transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="transaction",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transaction deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Transaction not found")
     * )
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
