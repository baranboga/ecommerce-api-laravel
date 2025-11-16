<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class CartService
{
    /**
     * Kullanıcının sepetini getirir veya oluşturur
     */
    public function getOrCreateCart(User $user): Cart
    {
        $cart = Cart::with(['items.product'])->where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
            $cart->load('items.product');
        }

        return $cart;
    }

    /**
     * Sepete ürün ekler
     */
    public function addItem(User $user, int $productId, int $quantity): Cart
    {
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = Product::findOrFail($productId);

        // Stok kontrolü
        if ($product->stock_quantity < $quantity) {
            throw new \Exception('Yeterli stok yok', 400);
        }

        // Sepette zaten varsa miktarı artır
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock_quantity < $newQuantity) {
                throw new \Exception('Yeterli stok yok', 400);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cart->load('items.product');
    }

    /**
     * Sepet ürün miktarını günceller
     */
    public function updateItem(User $user, int $productId, int $quantity): Cart
    {
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            throw new \Exception('Sepet bulunamadı', 404);
        }

        $product = Product::findOrFail($productId);

        // Stok kontrolü
        if ($product->stock_quantity < $quantity) {
            throw new \Exception('Yeterli stok yok', 400);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            throw new \Exception('Sepet öğesi bulunamadı', 404);
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return $cart->load('items.product');
    }

    /**
     * Sepetten ürün çıkarır
     */
    public function removeItem(User $user, int $productId): void
    {
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            throw new \Exception('Sepet bulunamadı', 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            throw new \Exception('Sepet öğesi bulunamadı', 404);
        }

        $cartItem->delete();
    }

    /**
     * Sepeti temizler
     */
    public function clearCart(User $user): void
    {
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            throw new \Exception('Sepet bulunamadı', 404);
        }

        $cart->items()->delete();
    }
}

