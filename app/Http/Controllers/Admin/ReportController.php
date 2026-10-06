<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $period = $request->get('period', 'monthly'); // today, weekly, monthly, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = Order::with(['user', 'customer', 'courierService', 'items.product'])
            ->whereIn('order_status', ['processing', 'shipped', 'delivered', 'confirmed']);

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                $title = 'আজকের বিক্রয় রিপোর্ট (' . Carbon::today()->format('d M Y') . ')';
                break;
            case 'weekly':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $title = 'এই সপ্তাহের বিক্রয় রিপোর্ট (' . Carbon::now()->startOfWeek()->format('d M') . ' - ' . Carbon::now()->endOfWeek()->format('d M Y') . ')';
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
                    $title = 'কাস্টম সময়কালের বিক্রয় রিপোর্ট (' . $startDate . ' থেকে ' . $endDate . ')';
                } else {
                    $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    $title = 'চলতি মাসের বিক্রয় রিপোর্ট (' . Carbon::now()->format('F Y') . ')';
                }
                break;
            case 'monthly':
            default:
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                $title = 'চলতি মাসের বিক্রয় রিপোর্ট (' . Carbon::now()->format('F Y') . ')';
                break;
        }

        $orders = $query->orderBy('id', 'desc')->get();

        // Summary Calculations
        $totalSales = $orders->sum('grand_total');
        $totalOrders = $orders->count();
        $totalItemsSold = $orders->flatMap->items->sum('quantity');
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Daily breakdown data for charts/tables
        $dailyData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(id) as total_orders'),
                DB::raw('SUM(grand_total) as total_revenue')
            )
            ->whereIn('order_status', ['processing', 'shipped', 'delivered', 'confirmed'])
            ->when($period === 'today', fn($q) => $q->whereDate('created_at', Carbon::today()))
            ->when($period === 'weekly', fn($q) => $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
            ->when($period === 'monthly', fn($q) => $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]))
            ->when($period === 'custom' && $startDate && $endDate, fn($q) => $q->whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();

        // Top Selling Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->whereHas('order', function($q) use ($period, $startDate, $endDate) {
                $q->whereIn('order_status', ['processing', 'shipped', 'delivered', 'confirmed']);
                if ($period === 'today') $q->whereDate('created_at', Carbon::today());
                elseif ($period === 'weekly') $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                elseif ($period === 'monthly') $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                elseif ($period === 'custom' && $startDate && $endDate) $q->whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        return view('admin.reports.sales', compact(
            'orders', 'totalSales', 'totalOrders', 'totalItemsSold',
            'averageOrderValue', 'dailyData', 'topProducts', 'period',
            'startDate', 'endDate', 'title'
        ));
    }
}
