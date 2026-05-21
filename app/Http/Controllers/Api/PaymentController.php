<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
      public function pay(Request $request)
    {
     $transaction_id = uniqid();

    return response()->json([
        "payment_url" => "https://payment-gateway.com/" . $transaction_id
    ]);
    }

    public function webhook(Request $request)
    {
       $order = Order::find($request->order_id);

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    if ($request->status == "ACCEPTED") {
        $order->status = 'paid';
        $order->save();
    }

    return response()->json(['message' => 'OK']);
}
}
