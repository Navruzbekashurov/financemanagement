<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Goal\StoreGoalRequest;
use App\Http\Requests\Goal\UpdateGoalRequest;
use App\Http\Resources\GoalResource;
use App\Models\Goal;
use App\DTOs\Goal\StoreGoalDto;
use App\DTOs\Goal\UpdateGoalDto;
use App\Services\GoalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class GoalController extends Controller
{
    public function __construct(protected GoalService $goalService) {}

    /**
     * @OA\Get(
     *     path="/api/goals",
     *     summary="List all goals",
     *     tags={"Goals"},
     *     @OA\Response(
     *         response=200,
     *         description="List of goals",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/GoalResource"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(GoalResource::collection(Goal::with('category')->latest()->get()));
    }

    /**
     * @OA\Post(
     *     path="/api/goals",
     *     summary="Create a new goal",
     *     tags={"Goals"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Goal created",
     *         @OA\JsonContent(ref="#/components/schemas/GoalResource")
     *     )
     * )
     */
    public function store(StoreGoalRequest $request)
    {
        $dto = StoreGoalDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $goal = $this->goalService->create($dto);
        return new GoalResource($goal);
    }

    /**
     * @OA\Get(
     *     path="/api/goals/{goal}",
     *     summary="Get a single goal",
     *     tags={"Goals"},
     *     @OA\Parameter(
     *         name="goal",
     *         in="path",
     *         required=true,
     *         description="ID of goal",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Goal details",
     *         @OA\JsonContent(ref="#/components/schemas/GoalResource")
     *     ),
     *     @OA\Response(response=404, description="Goal not found")
     * )
     */
    public function show(Goal $goal): JsonResponse
    {
        return response()->json(new GoalResource($goal->load('category')));
    }

    /**
     * @OA\Put(
     *     path="/api/goals/{goal}",
     *     summary="Update a goal",
     *     tags={"Goals"},
     *     @OA\Parameter(
     *         name="goal",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated goal",
     *         @OA\JsonContent(ref="#/components/schemas/GoalResource")
     *     )
     * )
     */
    public function update(UpdateGoalRequest $request, Goal $goal): JsonResponse
    {
        $dto = UpdateGoalDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $updated = $this->goalService->update($goal, $dto);
        return response()->json(new GoalResource($updated->load('category')));
    }

    /**
     * @OA\Delete(
     *     path="/api/goals/{goal}",
     *     summary="Delete a goal",
     *     tags={"Goals"},
     *     @OA\Parameter(
     *         name="goal",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Goal deleted",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Goal deleted successfully"))
     *     ),
     *     @OA\Response(response=404, description="Goal not found")
     * )
     */
    public function destroy(Goal $goal): JsonResponse
    {
        $goal->delete();
        return response()->json(['message' => 'Goal deleted successfully']);
    }
}
