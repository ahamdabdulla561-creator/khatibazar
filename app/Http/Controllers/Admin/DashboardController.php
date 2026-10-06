<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('order_status', 'delivered')->sum('grand_total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $confirmedOrders = Order::where('order_status', 'confirmed')->count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $shippedOrders = Order::where('order_status', 'shipped')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();

        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        
        $lowStockProducts = Product::with('variants')
            ->where('stock', '<=', 5)
            ->orWhereHas('variants', fn($v) => $v->where('stock', '<=', 5))
            ->take(10)
            ->get();

        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'pendingOrders', 'confirmedOrders',
            'processingOrders', 'shippedOrders', 'deliveredOrders', 'cancelledOrders',
            'totalCustomers', 'totalProducts', 'lowStockProducts', 'recentOrders'
        ));
    }
}
