<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Kategori yönetimi"
 * )
 */
class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Tüm kategorileri listele",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Kategoriler listelendi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return ResponseHelper::success($categories, 'Kategoriler listelendi');
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Yeni kategori oluştur (Admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Elektronik", minLength=3),
     *             @OA\Property(property="description", type="string", example="Elektronik ürünler")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kategori oluşturuldu",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Kategori oluşturuldu")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Yetkisiz erişim"),
     *     @OA\Response(response=422, description="Validasyon hatası")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors()->toArray());
        }

        $category = $this->categoryService->createCategory($request->only(['name', 'description']));

        return ResponseHelper::success($category, 'Kategori oluşturuldu', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Kategori güncelle (Admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Elektronik"),
     *             @OA\Property(property="description", type="string", example="Elektronik ürünler")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori güncellendi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Kategori bulunamadı"),
     *     @OA\Response(response=401, description="Yetkisiz erişim")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|min:3',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors()->toArray());
        }

        try {
            $category = $this->categoryService->updateCategory($id, $request->only(['name', 'description']));

            return ResponseHelper::success($category, 'Kategori güncellendi');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Kategori sil (Admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori silindi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Kategori bulunamadı"),
     *     @OA\Response(response=401, description="Yetkisiz erişim")
     * )
     */
    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);

            return ResponseHelper::success(null, 'Kategori silindi');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }
}
