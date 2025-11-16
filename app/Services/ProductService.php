<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    /**
     * Ürünleri filtreleyerek ve sayfalayarak listeler
     */
    public function getProducts(Request $request)
    {
        $query = Product::with('category');

        // Filtreleme
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sayfalama
        $limit = $request->get('limit', 20);
        $page = $request->get('page', 1);

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Ürün detayını getirir
     */
    public function getProductById(int $id): Product
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            throw new \Exception('Ürün bulunamadı', 404);
        }

        return $product;
    }

    /**
     * Yeni ürün oluşturur
     */
    public function createProduct(array $data): Product
    {
        $product = Product::create($data);
        return $product->load('category');
    }

    /**
     * Ürünü günceller
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = Product::find($id);

        if (!$product) {
            throw new \Exception('Ürün bulunamadı', 404);
        }

        $product->update($data);
        return $product->load('category');
    }

    /**
     * Ürünü siler
     */
    public function deleteProduct(int $id): void
    {
        $product = Product::find($id);

        if (!$product) {
            throw new \Exception('Ürün bulunamadı', 404);
        }

        $product->delete();
    }
}

