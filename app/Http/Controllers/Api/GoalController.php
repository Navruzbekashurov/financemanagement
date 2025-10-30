<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Goal\StoreGoalDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Goal\GoalRequest;
use App\Models\Goal;
use App\Services\GoalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function __construct(protected GoalService $goalService)
    {
    }

    public function index(Request $request)
    {
        $goals = Goal::where('user_id', $request->user()->id)->get();
        return response()->json($goals);
    }

    public function store(GoalRequest $request)
    {
        $dto = StoreGoalDto::fromRequest($request);
        $goal = $this->goalService->store($dto, Auth::user());

        return response()->json([
            'message' => 'Goal created',
            'goal' => $goal
        ], 201);
    }

    public function show(Goal $goal)
    {
        // $this->authorize('view', $goal);
        return response()->json($goal);
    }

    public function update(GoalRequest $request, Goal $goal)
    {
        // $this->authorize('update', $goal);
        $dto = StoreGoalDto::fromRequest($request);
        $goal = $this->goalService->update($goal, $dto);

        return response()->json([
            'message' => 'Goal Updated',
            'goal' => $goal
        ]);
    }

    public function destroy(Goal $goal)
    {
        // $this->authorize('delete', $goal);
        $goal->delete();

        return response()->json(['message' => 'Goal deleted']);
    }
}
