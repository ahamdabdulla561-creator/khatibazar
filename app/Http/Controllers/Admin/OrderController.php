<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CourierService;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\OrderMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_phone', 'like', "%{$q}%");
            });
        }

        $orders = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'items.variant', 'user', 'courierService', 'messages'])->findOrFail($id);
        $couriers = CourierService::orderBy('sort_order', 'asc')->get();
        return view('admin.orders.show', compact('order', 'couriers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,unpaid,refunded',
            'courier_service_id' => 'nullable|exists:courier_services,id',
            'courier_tracking_id' => 'nullable|string|max:100',
        ]);

        $order = Order::with('items')->findOrFail($id);
        $oldOrderStatus = $order->order_status;
        $newOrderStatus = $request->order_status;

        $courierName = $order->courier_name;
        $courierServiceId = $order->courier_service_id;

        if ($request->has('courier_service_id')) {
            if ($request->filled('courier_service_id')) {
                $cService = CourierService::find($request->courier_service_id);
                if ($cService) {
                    $courierServiceId = $cService->id;
                    $courierName = $cService->name;
                }
            } else {
                $courierServiceId = null;
                $courierName = null;
            }
        }

        DB::transaction(function () use ($order, $oldOrderStatus, $newOrderStatus, $courierServiceId, $courierName, $request) {
            // Restore stock if transitioning to cancelled
            if ($oldOrderStatus !== 'cancelled' && $newOrderStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->increment('stock', $item->quantity);
                        }
                    } elseif ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            // Deduct stock if un-cancelling
            if ($oldOrderStatus === 'cancelled' && $newOrderStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->decrement('stock', $item->quantity);
                        }
                    } elseif ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->decrement('stock', $item->quantity);
                        }
                    }
                }
            }

            $order->update([
                'order_status' => $newOrderStatus,
                'payment_status' => $request->payment_status,
                'courier_service_id' => $courierServiceId,
                'courier_name' => $courierName,
                'courier_tracking_id' => $request->courier_tracking_id,
            ]);
        });

        AuditLog::log('Order Status Changed', "অর্ডার #{$order->order_number} স্ট্যাটাস পরিবর্তন করা হয়েছে ({$oldOrderStatus} -> {$newOrderStatus})।");

        return redirect()->back()->with('success', 'অর্ডার স্ট্যাটাস ও কুরিয়ার তথ্য সফলভাবে আপডেট করা হয়েছে!');
    }

    public function sendOrderMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($id);

        OrderMessage::create([
            'order_id' => $order->id,
            'sender_type' => 'admin',
            'sender_name' => auth()->user()->name ?? 'Khati Bajar Admin',
            'message' => $request->message,
            'is_read' => true,
        ]);

        AuditLog::log('Admin Order Message Sent', "অর্ডার #{$order->order_number} এর জন্য মেসেজ পাঠানো হয়েছে।");

        return redirect()->back()->with('success', 'কাস্টমারকে সফলভাবে মেসেজ পাঠানো হয়েছে!');
    }
}
