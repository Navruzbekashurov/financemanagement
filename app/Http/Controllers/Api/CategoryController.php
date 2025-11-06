<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\DTOs\Category\StoreCategoryDto;
use App\DTOs\Category\UpdateCategoryDto;
use App\Services\CategoryService;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        $categories = Category::latest()->get();
        return response()->json(CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $dto = StoreCategoryDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $category = $this->categoryService->create($dto);

        return response()->json(new CategoryResource($category), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $dto = UpdateCategoryDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $updated = $this->categoryService->update($category, $dto);

        return response()->json(new CategoryResource($updated));
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
