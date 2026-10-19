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
            'sender_name' => auth()->user()->name ?? 'Khati Bazar Admin',
            'message' => $request->message,
            'is_read' => true,
        ]);

        AuditLog::log('Admin Order Message Sent', "অর্ডার #{$order->order_number} এর জন্য মেসেজ পাঠানো হয়েছে।");

        return redirect()->back()->with('success', 'কাস্টমারকে সফলভাবে মেসেজ পাঠানো হয়েছে!');
    }

    public function sendToSteadfast(Request $request, $id, \App\Services\SteadfastService $steadfast)
    {
        $order = Order::with('items')->findOrFail($id);

        if (!$steadfast->isConfigured()) {
            return redirect()->back()->with('error', 'স্টেডফাস্ট API Key এবং Secret Key কনফিগার করা নেই। অনুগ্রহ করে সেটিংস পেজে গিয়ে যুক্ত করুন।');
        }

        $customCod = $request->filled('cod_amount') ? (float)$request->cod_amount : null;
        $customNote = $request->filled('note') ? $request->note : null;

        $result = $steadfast->createOrder($order, $customCod, $customNote);

        if (!$result['success']) {
            return redirect()->back()->with('error', 'স্টেডফাস্ট বুকিং ব্যর্থ হয়েছে: ' . $result['message']);
        }

        $consignment = $result['consignment'] ?? [];
        $trackingCode = $consignment['tracking_code'] ?? null;
        $consignmentId = $consignment['consignment_id'] ?? null;

        // Find or create Steadfast courier service record
        $steadfastCourier = CourierService::firstOrCreate(
            ['code' => 'steadfast'],
            [
                'name' => 'স্টেডফাস্ট কুরিয়ার (Steadfast)',
                'tracking_url_template' => 'https://steadfast.com.bd/t/{tracking_code}',
                'status' => 'active',
                'sort_order' => 1,
            ]
        );

        $order->update([
            'courier_service_id' => $steadfastCourier->id,
            'courier_name' => $steadfastCourier->name,
            'courier_tracking_id' => $trackingCode ?? $consignmentId,
            'order_status' => ($order->order_status === 'pending' || $order->order_status === 'confirmed') ? 'processing' : $order->order_status,
        ]);

        AuditLog::log('Steadfast Parcel Booked', "অর্ডার #{$order->order_number} স্টেডফাস্টে বুক করা হয়েছে (Tracking Code: {$trackingCode}, CID: {$consignmentId})।");

        return redirect()->back()->with('success', "স্টেডফাস্টে পার্সেল সফলভাবে বুক হয়েছে! ট্র্যাকিং কোড: {$trackingCode} (CID: {$consignmentId})");
    }

    public function checkSteadfastStatus($id, \App\Services\SteadfastService $steadfast)
    {
        $order = Order::findOrFail($id);

        if (empty($order->courier_tracking_id)) {
            return redirect()->back()->with('error', 'এই অর্ডারে কোনো কুরিয়ার ট্র্যাকিং কোড যুক্ত নেই।');
        }

        $result = $steadfast->getStatusByInvoice($order->order_number);
        if (!$result['success']) {
            $result = $steadfast->getStatusByTrackingCode($order->courier_tracking_id);
        }

        if ($result['success']) {
            $status = $result['delivery_status'];
            $statusLabels = [
                'pending' => 'পেন্ডিং (Pending)',
                'in_review' => 'রিভিউতে রয়েছে (In Review)',
                'delivered' => 'ডেলিভারি সম্পন্ন (Delivered)',
                'cancelled' => 'বাতিল (Cancelled)',
                'hold' => 'হোল্ড (Hold)',
                'in_transit' => 'ডেলিভারির পথে (In Transit)',
                'unknown' => 'অজ্ঞাত (Unknown)',
            ];
            $readable = $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));

            // If delivered, also update our order status if not already delivered
            if ($status === 'delivered' && $order->order_status !== 'delivered') {
                $order->update(['order_status' => 'delivered', 'payment_status' => 'paid']);
            }

            return redirect()->back()->with('success', "স্টেডফাস্ট ডেলিভারি স্ট্যাটাস: {$readable}");
        }

        return redirect()->back()->with('error', 'স্টেডফাস্ট স্ট্যাটাস চেক ব্যর্থ হয়েছে: ' . ($result['message'] ?? 'তথ্য পাওয়া যায়নি'));
    }
}
