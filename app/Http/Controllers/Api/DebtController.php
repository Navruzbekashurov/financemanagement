<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Debt\StoreDebtDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Debt\StoreDebtRequest;
use App\Http\Requests\Debt\UpdateDebtRequest;
use App\Http\Resources\DebtResource;
use App\Models\Debt;
use App\DTOs\Debt\UpdateDebtDto;
use App\Services\DebtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Debts",
 *     description="API Endpoints for managing debts"
 * )
 */
class DebtController extends Controller
{
    public function __construct(protected DebtService $debetService) {}

    /**
     * @OA\Get(
     *     path="/api/auth/debts",
     *     summary="Get all debts",
     *     tags={"Debts"},
     *     @OA\Response(
     *         response=200,
     *         description="List of debts",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/DebtResource"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(DebtResource::collection(Debt::with('category')->latest()->get()));
    }

    /**
     * @OA\Post(
     *     path="/api/auth/debts",
     *     summary="Create a new debt",
     *     tags={"Debts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DebtCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Debt created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DebtResource")
     *     )
     * )
     */
    public function store(StoreDebtRequest $request): JsonResponse
    {
        $dto = StoreDebtDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $debet = $this->debetService->create($dto);
        return response()->json(new DebtResource($debet->load('category')), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/debts/{id}",
     *     summary="Get a single debt",
     *     tags={"Debts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Debt details",
     *         @OA\JsonContent(ref="#/components/schemas/DebtResource")
     *     ),
     *     @OA\Response(response=404, description="Debt not found")
     * )
     */
    public function show(Debt $debet): JsonResponse
    {
        return response()->json(new DebtResource($debet->load('category')));
    }

    /**
     * @OA\Put(
     *     path="/api/auth/debts/{id}",
     *     summary="Update a debt",
     *     tags={"Debts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DebtUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Debt updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DebtResource")
     *     ),
     *     @OA\Response(response=404, description="Debt not found")
     * )
     */
    public function update(UpdateDebtRequest $request, Debt $debt): JsonResponse
    {
        $dto = UpdateDebtDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $updated = $this->debetService->update($debt, $dto);
        return response()->json(new DebtResource($updated->load('category')));
    }

    /**
     * @OA\Delete(
     *     path="/api/auth/debts/{id}",
     *     summary="Delete a debt",
     *     tags={"Debts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Debt deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Debt not found")
     * )
     */
    public function destroy(Debt $debet): JsonResponse
    {
        $debet->delete();
        return response()->json(['message' => 'Debt deleted successfully']);
    }
}
