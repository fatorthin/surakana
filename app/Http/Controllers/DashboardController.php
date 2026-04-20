<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        if (! $request->user()->isAdmin()) {
            return redirect()->route('orders.index');
        }

        return view('dashboard', [
            'summary' => [
                'orders_pending' => Order::query()->where('status', Order::STATUS_PENDING)->count(),
                'orders_processing' => Order::query()->where('status', Order::STATUS_PROCESSING)->count(),
                'revenue' => (float) Finance::query()->where('type', Finance::TYPE_INCOME)->sum('amount'),
                'expenses' => (float) Finance::query()->where('type', Finance::TYPE_EXPENSE)->sum('amount'),
                'active_products' => Product::query()->where('is_active', true)->count(),
            ],
            'latestOrders' => Order::query()->with('user')->latest()->take(5)->get(),
        ]);
    }
}
