<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Midtrans;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        // Set konfigurasi Midtrans
        Midtrans\Config::$serverKey = config('app.midtrans.server_key');
        Midtrans\Config::$isProduction = config('app.midtrans.is_production');
        Midtrans\Config::$isSanitized = config('app.midtrans.is_sanitized');
        Midtrans\Config::$is3ds = config('app.midtrans.is_3ds');

        // Membuat order ID unik
        $orderId = uniqid();

        $user = auth()->user();
        $userData = User::findOrFail($user->id);

        $product = Products::findOrFail($request->input("idProduct"));

        // $stok = $product->stock;
        // $qty = $request->input("quantity");

        // $totalQty = ($stok >= $qty) ? $qty : $stok;
        // if ($totalQty >= 0){
        //     $product->stock = $stok - $qty;
        //     $product->save();
        // }
        
        // Data transaksi
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => $product->price * $request->input("quantity"), // Harga total
        ];

        $itemDetails = [
            [
                'id' => $product->id,
                'price' => $product->price,
                'quantity' => $request->input("quantity"),
                'name' => $product->name, //untuk sementara menghindari error
            ],
        ];

        $transaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            // 'customer_details' => $customerDetails,
        ];

        try {
            $snapToken = Midtrans\Snap::getSnapToken($transaction);
            $order = Orders::create([
                'order_id' => $orderId,
                'first_name' => $request->input("first_name"),
                'last_name' => $request->input("last_name"),
                'address' => $request->input("address"),
                'total_price' => $transactionDetails['gross_amount'],
                'quantity' => $request->input("quantity"),
                'status' => 'pending',
                'product_id' => $product->id,
                'user_id' => $userData->id,
            ]);
            
            return response()->json(['snap_token' => $snapToken, 'order' => $order]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}