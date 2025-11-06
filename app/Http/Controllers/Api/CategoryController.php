<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\DTOs\Category\StoreCategoryDto;
use App\DTOs\Category\UpdateCategoryDto;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        return response()->json(Category::latest()->get());
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $dto = StoreCategoryDto::fromRequest($request);
        $dto->user_id = Auth::id();

        $category = $this->categoryService->create($dto);

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $dto = UpdateCategoryDto::fromRequest($request);
        $updated = $this->categoryService->update($category, $dto);
        return response()->json($updated);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
