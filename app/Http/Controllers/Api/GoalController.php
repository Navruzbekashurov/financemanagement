<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Goal\StoreGoalDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Goal\GoalRequest;
use App\Http\Resources\GoalResource;
use App\Models\Goal;
use App\Services\GoalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class GoalController extends Controller
{
    public function __construct(protected GoalService $goalService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/goals",
     *     summary="Foydalanuvchining barcha maqsadlarini olish",
     *     tags={"Goals"},
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(ref="#/components/schemas/GoalResource"))
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $goals = Goal::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return GoalResource::collection($goals);
    }

    /**
     * @OA\Post(
     *     path="/api/goals",
     *     summary="Yangi maqsad yaratish",
     *     tags={"Goals"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalResource")
     *     ),
     *     @OA\Response(response=201, description="Goal created", @OA\JsonContent(ref="#/components/schemas/GoalResource"))
     * )
     */
    public function store(GoalRequest $request)
    {
        $dto = StoreGoalDto::fromRequest($request);
        $goal = $this->goalService->store($dto, Auth::user());

        return (new GoalResource($goal))
            ->additional(['message' => 'Goal created'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/goals/{id}",
     *     summary="Bitta maqsadni ko‘rish",
     *     tags={"Goals"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(ref="#/components/schemas/GoalResource"))
     * )
     */
    public function show(Goal $goal): GoalResource
    {
        // $this->authorize('view', $goal);
        return new GoalResource($goal);
    }

    /**
     * @OA\Put(
     *     path="/api/goals/{id}",
     *     summary="Maqsadni yangilash",
     *     tags={"Goals"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/GoalResource")
     *     ),
     *     @OA\Response(response=200, description="Goal updated", @OA\JsonContent(ref="#/components/schemas/GoalResource"))
     * )
     */
    public function update(GoalRequest $request, Goal $goal)
    {
        // $this->authorize('update', $goal);
        $dto = StoreGoalDto::fromRequest($request);
        $goal = $this->goalService->update($goal, $dto);

        return (new GoalResource($goal))
            ->additional(['message' => 'Goal updated']);
    }

    /**
     * @OA\Delete(
     *     path="/api/goals/{id}",
     *     summary="Maqsadni o‘chirish",
     *     tags={"Goals"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Goal deleted")
     * )
     */
    public function destroy(Goal $goal)
    {
        // $this->authorize('delete', $goal);
        $goal->delete();

        return response()->json(['message' => 'Goal deleted']);
    }
}
