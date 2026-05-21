<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
// use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

public function index()
{
    return Order::with('items.product')->latest()->get();
}


public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $order->status = $request->status;
    $order->save();

    return response()->json(['message' => 'Statut mis à jour']);
}



      public function store(Request $request)
    {
    DB::beginTransaction();

    try {

        // 1. Créer la commande
        $order = Order::create([
            'total' => $request->total,
            'payment_method' => $request->payment_method,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        // 2. Parcourir les produits
        foreach ($request->products as $item) {

            $product = Product::findOrFail($item['id']);

            // ❌ Vérifier stock
            if ($product->stock < $item['quantity']) {
                throw new \Exception('Stock insuffisant pour ' . $product->name);
            }

            // ✅ Réduire stock
            $product->decrement('stock', $item['quantity']);

            // ✅ Créer ligne commande
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // ✅ Tout est OK → on valide
        DB::commit();

        return response()->json([
            'message' => 'Commande créée avec succès',
            'order' => $order
        ]);

    } catch (\Exception $e) {

        // ❌ Erreur → on annule tout
        DB::rollback();

        return response()->json([
            'error' => $e->getMessage()
        ], 400);
    }
    }
}
