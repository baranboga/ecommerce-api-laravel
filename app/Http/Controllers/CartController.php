<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Cart",
 *     description="Sepet yönetimi"
 * )
 */
class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Sepeti görüntüle",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Sepet görüntülendi")
     * )
     */
    public function index()
    {
        $user = auth()->user();
        $cart = $this->cartService->getOrCreateCart($user);

        return ResponseHelper::success($cart, 'Sepet görüntülendi');
    }

    /**
     * @OA\Post(
     *     path="/api/cart/add",
     *     tags={"Cart"},
     *     summary="Sepete ürün ekle",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","quantity"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2, minimum=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Ürün sepete eklendi"),
     *     @OA\Response(response=400, description="Yeterli stok yok"),
     *     @OA\Response(response=422, description="Validasyon hatası")
     * )
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors()->toArray());
        }

        try {
            $user = auth()->user();
            $cart = $this->cartService->addItem($user, $request->product_id, $request->quantity);

            return ResponseHelper::success($cart, 'Ürün sepete eklendi', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), [], $e->getCode() ?: 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/cart/update",
     *     tags={"Cart"},
     *     summary="Sepet ürün miktarı güncelle",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","quantity"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=3, minimum=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Sepet güncellendi"),
     *     @OA\Response(response=404, description="Sepet öğesi bulunamadı")
     * )
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors()->toArray());
        }

        try {
            $user = auth()->user();
            $cart = $this->cartService->updateItem($user, $request->product_id, $request->quantity);

            return ResponseHelper::success($cart, 'Sepet güncellendi');
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            if ($code === 404) {
                return ResponseHelper::notFound($e->getMessage());
            }
            return ResponseHelper::error($e->getMessage(), [], $code);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/remove/{product_id}",
     *     tags={"Cart"},
     *     summary="Sepetten ürün çıkar",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="product_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Ürün sepetten çıkarıldı"),
     *     @OA\Response(response=404, description="Sepet öğesi bulunamadı")
     * )
     */
    public function remove($productId)
    {
        try {
            $user = auth()->user();
            $this->cartService->removeItem($user, $productId);

            return ResponseHelper::success(null, 'Ürün sepetten çıkarıldı');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/clear",
     *     tags={"Cart"},
     *     summary="Sepeti temizle",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Sepet temizlendi")
     * )
     */
    public function clear()
    {
        try {
            $user = auth()->user();
            $this->cartService->clearCart($user);

            return ResponseHelper::success(null, 'Sepet temizlendi');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }
}
