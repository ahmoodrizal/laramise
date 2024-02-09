<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Midtrans\CreateVirtualAccountService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $data = $request->validate([
            'address_id' => ['required', 'exists:addresses,id'],
            'shipping_service' => ['required'],
            'shipping_cost' => ['required', 'integer'],
            'payment_method' => ['required'],
            'payment_va_name' => ['nullable', 'string'],
            'total_cost' => ['required', 'integer'],
            'subtotal' => ['required', 'integer'],
            'products' => ['required', 'array'],
            'products.*.id' => ['exists:products,id']
        ]);

        $data['transaction_number'] = 'TRX' . rand(10000, 99999);
        $data['user_id'] = auth()->user()->id;

        $data['subtotal'] = 0;
        foreach ($request['products'] as $item) {
            $product = Product::find($item['product_id']);
            $data['subtotal'] += $product->price * $item['quantity'];
        }

        $data['total_cost'] = $data['subtotal'] + $request['shipping_cost'];

        $order = Order::create($data);

        foreach ($request['products'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity']
            ]);
        }

        // Integrate with Midtrans
        $midtrans = new CreateVirtualAccountService($order->load('user', 'orderItems'));
        $apiResponse = $midtrans->getVirtualAccount();

        $order->payment_va_number = $apiResponse->va_numbers[0]->va_number;
        $order->save();

        return response()->json([
            'message' => 'Order success',
            'order' => $order,
        ], 201);
    }

    public function orderById($id)
    {
        $order = Order::with('orderItems.product')->find($id);
        $order->load('user', 'address');

        return response()->json([
            'message' => 'success',
            'order' => $order,
        ], 200);
    }

    public function ordersByUser()
    {
        $orders = Order::whereBelongsTo(auth()->user())->latest()->get();

        if (!$orders) {
            return response()->json([
                'message' => 'Data not found',
                'orders' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Success fetch orders by user id',
            'orders' => $orders,
        ], 200);
    }

    public function checkStatus(Order $order)
    {
        return response()->json([
            'message' => 'Success',
            'status' => $order->status,
        ], 200);
    }
}
