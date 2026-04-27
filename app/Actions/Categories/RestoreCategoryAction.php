<?php

namespace App\Actions\Categories;

use App\Models\Category;

class RestoreCategoryAction
{
    /**
     * Restore a soft-deleted category.
     * 
     * @param int $id
     * @return bool
     */
    public function handle(int $id): bool
    {
        $category = Category::onlyTrashed()->find($id);

        if (!$category) {
            return false;
        }

        return $category->restore();
    }
}
