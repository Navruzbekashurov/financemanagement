<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Http\Requests\Goal\StoreGoalRequest;
use App\Http\Requests\Goal\UpdateGoalRequest;
use App\DTOs\Goal\StoreGoalDto;
use App\DTOs\Goal\UpdateGoalDto;
use App\Services\GoalService;
use Illuminate\Http\JsonResponse;

class GoalController extends Controller
{
    public function __construct(protected GoalService $goalService) {}

    public function index(): JsonResponse
    {
        return response()->json(Goal::with('category')->latest()->get());
    }

    public function store(StoreGoalRequest $request): JsonResponse
    {
        $dto = StoreGoalDto::fromRequest($request);
        $goal = $this->goalService->create($dto);
        return response()->json($goal, 201);
    }

    public function show(Goal $goal): JsonResponse
    {
        return response()->json($goal->load('category'));
    }

    public function update(UpdateGoalRequest $request, Goal $goal): JsonResponse
    {
        $dto = UpdateGoalDto::fromRequest($request);
        $updated = $this->goalService->update($goal, $dto);
        return response()->json($updated);
    }

    public function destroy(Goal $goal): JsonResponse
    {
        $goal->delete();
        return response()->json(['message' => 'Goal deleted successfully']);
    }
}
