<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()->paginate(8);
        return view('pages.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('pages.order.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Order $order, Request $request)
    {
        $data = $request->validate([
            'shipping_resi' => ['nullable'],
            'status' => ['nullable', 'in:pending,paid,expired,delivered,canceled,on_delivery']
        ]);

        $order->update($data);

        if ($request['status'] == 'on_delivery') {
            // Send FCM Notify
            $this->sendNotificationToUser($order->user_id, 'Paket dikirim dengan no.resi ' . $request['shipping_resi']);
        }


        return redirect(route('order.index'))->with('success', 'Order has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sendNotificationToUser($userId, $message)
    {
        $user = User::find($userId);
        $token = $user->fcm_id;

        $messaging = app('firebase.messaging');
        $notify = Notification::create('Paket dalam perjalanan', $message);

        $message = CloudMessage::withTarget('token', $token)->withNotification($notify);

        $messaging->send($message);
    }
}
