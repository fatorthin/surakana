<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()->with('user')->latest()->paginate(12);

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::statuses(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::statuses(),
        ]);
    }

    public function create(): View
    {
        return view('admin.orders.create');
    }
}
