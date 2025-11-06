<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Debt\StoreDebtDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Debt\StoreDebtRequest;
use App\Http\Requests\Debt\UpdateDebtRequest;
use App\Models\Debt;
use App\DTOs\Debt\UpdateDebtDto;
use App\Services\DebtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    public function __construct(protected DebtService $debetService) {}

    public function index(): JsonResponse
    {
        return response()->json(Debt::with('category')->latest()->get());
    }

    public function store(StoreDebtRequest $request): JsonResponse
    {
        $dto = StoreDebtDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $debet = $this->debetService->create($dto);
        return response()->json($debet, 201);
    }

    public function show(Debt $debet): JsonResponse
    {
        return response()->json($debet->load('category'));
    }

    public function update(UpdateDebtRequest $request, Debt $debt): JsonResponse
    {
        $dto = UpdateDebtDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $updated = $this->debetService->update($debt, $dto);
        return response()->json($updated);
    }

    public function destroy(Debt $debet): JsonResponse
    {
        $debet->delete();
        return response()->json(['message' => 'Debt deleted successfully']);
    }
}
