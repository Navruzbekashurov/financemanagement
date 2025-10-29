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
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Transactions",
 *     description="Authenticated user's income and expense management"
 * )
 */
/**
 * @OA\Schema(
 *     schema="TransactionRequest",
 *     required={"category", "amount", "type", "date"},
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="category", type="string", example="Food"),
 *     @OA\Property(property="amount", type="number", format="float", example=25.50),
 *     @OA\Property(property="type", type="string", enum={"income","expense"}, example="expense"),
 *     @OA\Property(property="note", type="string", nullable=true, example="Lunch payment"),
 *     @OA\Property(property="date", type="string", format="date", example="2025-10-29")
 * )
 *
 * @OA\Schema(
 *     schema="UpdateTransactionRequest",
 *     required={"category", "amount", "type", "date"},
 *     @OA\Property(property="category", type="string", example="Utilities"),
 *     @OA\Property(property="amount", type="number", format="float", example=45.00),
 *     @OA\Property(property="type", type="string", enum={"income","expense"}, example="expense"),
 *     @OA\Property(property="note", type="string", nullable=true, example="Electricity bill"),
 *     @OA\Property(property="date", type="string", format="date", example="2025-10-29")
 * )
 */
class TransactionApiController extends Controller
{
    public function __construct(protected TransactionService $transactionService) {}

    /**
     * @OA\Get(
     *     path="/api/auth/transactions",
     *     summary="List all transactions for the authenticated user",
     *     tags={"Transactions"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful list retrieval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/TransactionResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return TransactionResource::collection($transactions);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/transactions/{id}",
     *     summary="Show a single transaction by ID",
     *     tags={"Transactions"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Transaction found",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
     *     ),
     *     @OA\Response(response=403, description="Access denied")
     * )
     */
    public function show(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return new TransactionResource($transaction);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/transactions",
     *     summary="Create a new transaction",
     *     tags={"Transactions"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionRequest")
     *     ),
     *     @OA\Response(response=201, description="Transaction created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Put(
     *     path="/api/auth/transactions/{id}",
     *     summary="Update a transaction",
     *     tags={"Transactions"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTransactionRequest")
     *     ),
     *     @OA\Response(response=200, description="Transaction updated successfully"),
     *     @OA\Response(response=403, description="Access denied")
     * )
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
     * @OA\Delete(
     *     path="/api/auth/transactions/{id}",
     *     summary="Delete a transaction",
     *     tags={"Transactions"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Transaction ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Transaction deleted successfully"),
     *     @OA\Response(response=403, description="Access denied")
     * )
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
