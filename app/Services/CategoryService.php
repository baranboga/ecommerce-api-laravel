<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    /**
     * Tüm kategorileri listeler
     */
    public function getAllCategories()
    {
        return Category::all();
    }

    /**
     * Yeni kategori oluşturur
     */
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Kategoriyi günceller
     */
    public function updateCategory(int $id, array $data): Category
    {
        $category = Category::find($id);

        if (!$category) {
            throw new \Exception('Kategori bulunamadı', 404);
        }

        if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        if (isset($data['description'])) {
            $category->description = $data['description'];
        }

        $category->save();

        return $category;
    }

    /**
     * Kategoriyi siler
     */
    public function deleteCategory(int $id): void
    {
        $category = Category::find($id);

        if (!$category) {
            throw new \Exception('Kategori bulunamadı', 404);
        }

        $category->delete();
    }
}

