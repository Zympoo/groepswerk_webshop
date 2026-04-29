<?php

namespace App\Actions\Categories;

use App\Models\Category;
use Illuminate\Support\Str;

class StoreCategoryAction
{
    /**
     * Create or update a category.
     * 
     * @param array $data
     * @param Category|null $category
     * @return Category
     */
    public function handle(array $data, ?Category $category = null): Category
    {
        // Senior Reflex: Ensure slug is always generated if not provided or if name changed
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($category) {
            $category->update($data);
            return $category;
        }

        return Category::create($data);
    }
}
