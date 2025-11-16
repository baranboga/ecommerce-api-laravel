<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\OrderService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Sipariş yönetimi"
 * )
 */
class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Kullanıcının siparişlerini listele",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Siparişler listelendi")
     * )
     */
    public function index()
    {
        $user = auth()->user();
        $orders = $this->orderService->getUserOrders($user);

        return ResponseHelper::success($orders, 'Siparişler listelendi');
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Sipariş oluştur",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Sipariş oluşturuldu",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Sipariş oluşturuldu")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Sepet boş veya stok yetersiz")
     * )
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $order = $this->orderService->createOrderFromCart($user);

            return ResponseHelper::success($order, 'Sipariş oluşturuldu', 201);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), [], $e->getCode() ?: 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Sipariş detayı",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Sipariş detayı"),
     *     @OA\Response(response=404, description="Sipariş bulunamadı")
     * )
     */
    public function show($id)
    {
        try {
            $user = auth()->user();
            $order = $this->orderService->getOrderById($user, $id);

            return ResponseHelper::success($order, 'Sipariş detayı');
        } catch (\Exception $e) {
            return ResponseHelper::notFound($e->getMessage());
        }
    }
}
