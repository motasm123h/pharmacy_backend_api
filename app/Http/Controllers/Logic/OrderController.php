<?php

namespace App\Http\Controllers\Logic;

use App\Models\Cart;
use App\Models\User;
use App\Models\Depot;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewNotification;
use App\Notifications\ResultOrderNotification;
use App\Notifications\LocationOrderNotification;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cartItems = Cart::where([
            ['user_id', auth()->user()->id]
        ])->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'no item found'
            ]);
        } else {

            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item->quantity * $item->price;
            }
            $products = json_encode($cartItems);
            $atter = $request->validate([
                'shipping_address' => ['required'],
            ]);

            $order = Order::create([
                'user_id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'phone_number' => auth()->user()->phone_number,
                'shipping_address' => $atter['shipping_address'],
                'total_amount' => $totalAmount,
                'products' => $products,
            ]);

            $Warehouse_owner = User::where('id', Depot::where('id', $cartItems['depot_id'])->first())->frist();
            $Warehouse_owner->notify(new NewNotification($order));
            $cartItemDelete = auth()->user()->carts()->delete();
            return response()->json([
                'order' => $order,
            ]);
        }
    }


    public function getAuthOrder()
    {
        $userOrders = auth()->user()->orders()->get();
        foreach ($userOrders as $order) {
            $order->products = json_decode($order['products'], true);
        }
        return response()->json([
            'message' => $userOrders
        ]);
    }


    public function getAlOrder()
    {
        $userOrders = Order::all();

        foreach ($userOrders as $order) {
            $order->products = json_decode($order['products'], true);
        }

        return response()->json([
            'message' => $userOrders
        ]);
    }

    public function updateOrder(Request $req, $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        if (!$order) {
            return response()->json([
                'message' => 'order not found'
            ]);
        }
        $order->update([
            'location' => $req->input('location'),
        ]);

        $order->products = json_decode($order['products'], true);
        $user = User::where('id', $order['user_id'])->first();
        $user->notify(new LocationOrderNotification($order));
        return response()->json([
            'order' => $order,
        ]);
    }


    public function AcceptOrder($order_id)
    {
        $order = Order::where('id', $order_id)->first();
        if (!$order) {
            return response()->json([
                'message' => 'order not found'
            ]);
        }
        $order->update([
            'status' => 'accepted',
        ]);

        $order->products = json_decode($order['products'], true);
        $user = User::where('id', $order['user_id'])->first();
        $user->notify(new ResultOrderNotification($order));
        return response()->json([
            'order' => $order,
        ]);
    }

    public function PaidOrder($order_id)
    {
        $order = Order::where('id', $order_id)->first();
        if (!$order) {
            return response()->json([
                'message' => 'order not found'
            ]);
        }
        $order->update([
            'pyment_status' => 'paid',
        ]);

        return response()->json([
            'order' => $order,
        ]);
    }

    public function RejectOrder($order_id)
    {
        $order = Order::where('id', $order_id)->first();
        if (!$order) {
            return response()->json([
                'message' => 'order not found'
            ]);
        }
        $order->update([
            'status' => 'rejected',
        ]);

        $order->products = json_decode($order['products'], true);
        $user = User::where('id', $order['user_id'])->first();
        $user->notify(new ResultOrderNotification($order));
        return response()->json([
            'order' => $order,
        ]);
    }

    public function getShippedOrder()
    {
        $orders = Order::where('location', 'Shipped')->get();
        foreach ($orders as $order) {
            $order->products = json_decode($order['products'], true);
        }

        return response()->json([
            'orders' => $orders
        ]);
    }


    public function getInStockOrder()
    {
        $orders = Order::where('location', 'In Stock')->get();
        foreach ($orders as $order) {
            $order->products = json_decode($order['products'], true);
        }

        return response()->json([
            'orders' => $orders
        ]);
    }


    public function getArrivedOrder()
    {
        $orders = Order::where('location', 'Arrived')->get();
        foreach ($orders as $order) {
            $order->products = json_decode($order['products'], true);
        }
        
        return response()->json([
            'orders' => $orders
        ]);
    }


    public function getOrderWithStutas($stutas)
    {

        $orders = Order::where('status', $stutas)->get();
        foreach ($orders as $order) {
            $order->products = json_decode($order['products'], true);
        }

        return response()->json([
            'orders' => $orders
        ]);
    }
}
