<?php

namespace App\Services;

use App\Models\Category;
use App\DTOs\Category\StoreCategoryDto;
use App\DTOs\Category\UpdateCategoryDto;

class CategoryService
{
    public function create(StoreCategoryDto $dto): Category
    {
        return Category::create([
            'user_id' => $dto->user_id,
            'name' => $dto->name,
            'is_active' => $dto->is_active ?? true,
        ]);
    }


    public function update(Category $category, UpdateCategoryDto $dto): Category
    {
        $category->update([
            'name' => $dto->name,
            'is_active' => $dto->is_active,
        ]);

        return $category;
    }
}
