<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class OrderService
{
    /**
     * Kullanıcının siparişlerini listeler
     */
    public function getUserOrders(User $user)
    {
        return Order::with('items.product')->where('user_id', $user->id)->get();
    }

    /**
     * Sepetten sipariş oluşturur
     */
    public function createOrderFromCart(User $user): Order
    {
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception('Sepet boş', 400);
        }

        // Stok kontrolü ve toplam tutar hesaplama
        $totalAmount = 0;
        foreach ($cart->items as $cartItem) {
            $product = $cartItem->product;
            if ($product->stock_quantity < $cartItem->quantity) {
                throw new \Exception("{$product->name} için yeterli stok yok", 400);
            }
            $totalAmount += $product->price * $cartItem->quantity;
        }

        // Sipariş oluştur
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Sipariş öğelerini oluştur ve stok güncelle
        foreach ($cart->items as $cartItem) {
            $product = $cartItem->product;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $cartItem->quantity,
                'price' => $product->price, // Sipariş anındaki fiyat
            ]);

            // Stok güncelle
            $product->stock_quantity -= $cartItem->quantity;
            $product->save();
        }

        // Sepeti temizle
        $cart->items()->delete();

        return $order->load('items.product');
    }

    /**
     * Sipariş detayını getirir
     */
    public function getOrderById(User $user, int $orderId): Order
    {
        $order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            throw new \Exception('Sipariş bulunamadı', 404);
        }

        return $order;
    }
}

