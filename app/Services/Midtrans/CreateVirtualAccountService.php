<?php

namespace App\Services\Midtrans;

use Midtrans\CoreApi;

class CreateVirtualAccountService extends Midtrans
{
    protected $order;

    public function __construct($order)
    {
        parent::__construct();

        $this->order = $order;
    }

    public function getVirtualAccount()
    {

        $itemDetails = [];
        foreach ($this->order->orderItems as $item) {
            $itemDetails[] = [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ];
        }

        // Add Shipping Cost
        $itemDetails[] = [
            'id' => 'SHIPPING_COST',
            'price' => $this->order->shipping_cost,
            'quantity' => 1,
            'name' => 'SHIPPING_COST'
        ];

        $params = [
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => $this->order->transaction_number,
                'gross_amount' => $this->order->total_cost,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $this->order->user->name,
                'email' => $this->order->user->email,
                'phone' => $this->order->user->phone_number,
            ],
            'bank_transfer' => [
                'bank' => $this->order->payment_va_name,
            ]
        ];

        $response = CoreApi::charge($params);

        return $response;
    }
}
