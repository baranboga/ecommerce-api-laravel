<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Ürün yönetimi"
 * )
 */
class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Ürünleri listele (filtreleme ve sayfalama)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer"), description="Sayfa numarası"),
     *     @OA\Parameter(name="limit", in="query", @OA\Schema(type="integer"), description="Sayfa başına kayıt (varsayılan: 20)"),
     *     @OA\Parameter(name="category_id", in="query", @OA\Schema(type="integer"), description="Kategori filtresi"),
     *     @OA\Parameter(name="min_price", in="query", @OA\Schema(type="number"), description="Minimum fiyat"),
     *     @OA\Parameter(name="max_price", in="query", @OA\Schema(type="number"), description="Maximum fiyat"),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string"), description="Ürün adında arama"),
     *     @OA\Response(
     *         response=200,
     *         description="Ürünler listelendi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request);

        return ResponseHelper::success($products, 'Ürünler listelendi');
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Tek ürün detayı",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Ürün detayı"),
     *     @OA\Response(response=404, description="Ürün bulunamadı")
     * )
     */
    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);

            return ResponseHelper::success($product, 'Ürün detayı');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Yeni ürün ekle (Admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","stock_quantity","category_id"},
     *             @OA\Property(property="name", type="string", example="iPhone 15", minLength=3),
     *             @OA\Property(property="description", type="string", example="Apple iPhone 15"),
     *             @OA\Property(property="price", type="number", example=45000.00, minimum=0),
     *             @OA\Property(property="stock_quantity", type="integer", example=50, minimum=0),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Ürün oluşturuldu"),
     *     @OA\Response(response=401, description="Yetkisiz erişim"),
     *     @OA\Response(response=422, description="Validasyon hatası")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors()->toArray());
        }

        $product = $this->productService->createProduct($request->all());

        return ResponseHelper::success($product, 'Ürün oluşturuldu', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Ürün güncelle (Admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="iPhone 15"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", example=45000.00),
     *             @OA\Property(property="stock_quantity", type="integer", example=50),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Ürün güncellendi"),
     *     @OA\Response(response=404, description="Ürün bulunamadı"),
     *     @OA\Response(response=401, description="Yetkisiz erişim")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|min:3',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors()->toArray());
        }

        try {
            $product = $this->productService->updateProduct($id, $request->only(['name', 'description', 'price', 'stock_quantity', 'category_id']));

            return ResponseHelper::success($product, 'Ürün güncellendi');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Ürün sil (Admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Ürün silindi"),
     *     @OA\Response(response=404, description="Ürün bulunamadı"),
     *     @OA\Response(response=401, description="Yetkisiz erişim")
     * )
     */
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);

            return ResponseHelper::success(null, 'Ürün silindi');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }
}
