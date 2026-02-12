<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function showCart($user)
    {
        $cart = $this->cartRepository->getUserCart($user);

        if (! $cart) {
            return [
                'status' => 200,
                'data' => ['message' => 'Cart is empty']
            ];
        }

        return [
            'status' => 200,
            'data' => $cart
        ];
    }

    public function addToCart($user, array $data)
    {

        $product = Product::findOrFail($data['product_id']);
        if ($product->quantity < $data['quantity']) {
            return [
                'status' => 400,
                'data' => ['message' => 'Not enough stock available']
            ];
        }

        $cart = $this->cartRepository->createCart($user);

        $this->cartRepository->addItem(
            $cart,
            $data['product_id'],
            $data['quantity']
        );

        return [
            'status' => 201,
            'data' => ['message' => 'Product added to cart successfully']
        ];
    }

    public function updateCart($user, $quantity)
    {
        $cart = $this->cartRepository->getUserCart($user);

        if (! $cart) {
            return [
                'status' => 404,
                'data' => ['message' => 'Cart not found']
            ];
        }

        $this->cartRepository->updateCartItems($cart, $quantity);

        return [
            'status' => 200,
            'data' => ['message' => 'Cart updated successfully']
        ];
    }

    public function clearCart($user)
    {
        $cart = $this->cartRepository->getUserCart($user);

        if (! $cart) {
            return [
                'status' => 404,
                'data' => ['message' => 'Cart not found']
            ];
        }

        $this->cartRepository->deleteCart($cart);

        return [
            'status' => 200,
            'data' => ['message' => 'Cart cleared successfully']
        ];
    }
}
