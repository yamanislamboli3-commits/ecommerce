<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use Illuminate\Validation\Rules\Exists;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function show(Request $request)
    {
        $response = $this->cartService->showCart($request->user());

        return response()->json($response['data'], $response['status']);
    }

    public function add(Request $request)
    {
        
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
       

        $response = $this->cartService->addToCart($request->user(), $data);

        return response()->json($response['data'], $response['status']);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $response = $this->cartService->updateCart(
            $request->user(),
            $validated['quantity']
        );

        return response()->json($response['data'], $response['status']);
    }

    public function destroy(Request $request)
    {
        $response = $this->cartService->clearCart($request->user());

        return response()->json($response['data'], $response['status']);
    }
}
