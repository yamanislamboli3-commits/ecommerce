<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function checkout()
{
    $cart = Cart::with('items.product')
        ->where('user_id', Auth::id())
        ->first();

    if (!$cart || $cart->items->isEmpty()) {
        return response()->json(['message' => 'Cart is empty'], 400);
    }

    $total = 0;

    foreach ($cart->items as $item) {
        $total += $item->product->price * $item->quantity;
    }

    DB::transaction(function () use ($cart, $total) {

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pending'
        ]);

        foreach ($cart->items as $item) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        $cart->items()->delete();

    });

    return response()->json(['message' => 'Order created']);
}
public function index()
{
    $orders = Order::with('items.product')
        ->where('user_id', Auth::id())
        ->get();

    return response()->json($orders);
}
public function cancel($id)
{
    $order = Order::where('user_id', Auth::id())
        ->findOrFail($id);

    if ($order->status !== 'pending') {
        return response()->json([
            'message' => 'Order cannot be cancelled'
        ], 400);
    }

    $order->update([
        'status' => 'cancelled'
    ]);

    return response()->json([
        'message' => 'Order cancelled successfully'
    ]);
}
}
