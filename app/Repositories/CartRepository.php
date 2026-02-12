<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

class CartRepository
{
    public function getUserCart(User $user)
    {
        return $user->cart()
            ->with('items.product')
            ->first();
    }

    public function createCart(User $user)
    {
        return $user->cart()->firstOrCreate();
    }

   public function addItem(Cart $cart, $productId, $quantity)
{
    $item = $cart->items()
                 ->where('product_id', $productId)
                 ->first();

    if ($item) {
        $item->increment('quantity', $quantity);
        return $item;
    }

    return $cart->items()->create([
        'product_id' => $productId,
        'quantity'   => $quantity
    ]);
}


    public function updateCartItems(Cart $cart, $quantity)
    {
        return $cart->items()->update([
            'quantity' => $quantity
        ]);
    }

    public function deleteCart(Cart $cart)
    {
        return $cart->delete();
    }
}
