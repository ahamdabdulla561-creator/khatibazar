<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CourierService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function getCart()
    {
        if (auth()->check()) {
            return Cart::where('user_id', auth()->id())->first();
        }

        $sessionId = session()->getId();
        return Cart::where('session_id', $sessionId)->first();
    }

    public function index()
    {
        $cart = $this->getCart();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'আপনার কার্ট খালি! অনুগ্রহ করে চেকআউট করার পূর্বে কিছু পণ্য যুক্ত করুন।');
        }

        $cart->load(['items.product', 'items.variant']);

        $insideDhakaCharge = 150;
        $outsideDhakaCharge = 150;

        // Ensure Pathao and Steadfast exist and are the active courier services
        try {
            CourierService::updateOrCreate(
                ['code' => 'pathao'],
                ['name' => 'পাঠাও কুরিয়ার (Pathao)', 'status' => 'active', 'sort_order' => 1]
            );
            CourierService::updateOrCreate(
                ['code' => 'steadfast'],
                ['name' => 'স্টেডফাস্ট কুরিয়ার (Steadfast)', 'status' => 'active', 'sort_order' => 2]
            );
            CourierService::whereNotIn('code', ['pathao', 'steadfast'])->update(['status' => 'inactive']);
        } catch (\Throwable $e) {
            // Ignore if table not migrated
        }

        $couriers = CourierService::where('status', 'active')->orderBy('sort_order', 'asc')->get();

        return view('frontend.checkout', compact('cart', 'insideDhakaCharge', 'outsideDhakaCharge', 'couriers'));
    }

    public function store(Request $request)
    {
        if (!$request->filled('delivery_area')) {
            $request->merge(['delivery_area' => 'outside_dhaka']);
        }

        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_district' => 'required|string|max:100',
            'shipping_upazila' => 'required|string|max:100',
            'shipping_address' => 'required|string|max:1000',
            'delivery_area' => 'nullable|in:inside_dhaka,outside_dhaka',
            'courier_service_id' => 'nullable|exists:courier_services,id',
            'payment_method' => 'required|string|in:cod,bkash,nagad,rocket',
            'notes' => 'nullable|string|max:500',
        ];

        if (in_array($request->payment_method, ['bkash', 'nagad', 'rocket'])) {
            $rules['sender_number'] = 'required|string|max:20';
            $rules['transaction_id'] = 'required|string|max:100';
            $rules['payment_screenshot'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
        }

        $request->validate($rules);

        $screenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $screenshotPath = $request->file('payment_screenshot')->store('payments/screenshots', 'public');
        }

        $cart = $this->getCart();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'আপনার কার্টে কোনো পণ্য পাওয়া যায়নি।');
        }

        $cart->load(['items.product', 'items.variant']);

        // Stock and price validation before starting transaction
        foreach ($cart->items as $item) {
            if ($item->variant) {
                if ($item->variant->stock < $item->quantity) {
                    return redirect()->back()->with('error', "দুঃখিত, '{$item->product->name} ({$item->variant->name})' পণ্যটির প্রয়োজনীয় স্টক নেই (বর্তমান স্টক: {$item->variant->stock})।");
                }
            } else {
                if ($item->product->stock < $item->quantity) {
                    return redirect()->back()->with('error', "দুঃখিত, '{$item->product->name}' পণ্যটির প্রয়োজনীয় স্টক নেই (বর্তমান স্টক: {$item->product->stock})।");
                }
            }
        }

        // Flat 150 BDT nationwide delivery charge
        $deliveryCharge = 150.00;

        try {
            $order = DB::transaction(function () use ($request, $cart, $deliveryCharge, $screenshotPath) {
                $subtotal = 0;
                $orderItemsData = [];

                foreach ($cart->items as $item) {
                    // Re-fetch product & variant inside transaction for strict lock
                    $product = Product::lockForUpdate()->find($item->product_id);
                    $variant = $item->product_variant_id ? ProductVariant::lockForUpdate()->find($item->product_variant_id) : null;

                    if ($variant) {
                        if ($variant->stock < $item->quantity) {
                            throw new \Exception("পণ্য '{$product->name} ({$variant->name})' এর পর্যাপ্ত স্টক নেই।");
                        }
                        $unitPrice = $variant->price;
                        $variantName = $variant->name;
                        $sku = $variant->sku ?? $product->sku;
                        // Deduct variant stock
                        $variant->decrement('stock', $item->quantity);
                    } else {
                        if ($product->stock < $item->quantity) {
                            throw new \Exception("পণ্য '{$product->name}' এর পর্যাপ্ত স্টক নেই।");
                        }
                        $unitPrice = $product->effective_price;
                        $variantName = null;
                        $sku = $product->sku;
                        // Deduct product stock
                        $product->decrement('stock', $item->quantity);
                    }

                    $itemSubtotal = $unitPrice * $item->quantity;
                    $subtotal += $itemSubtotal;

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'product_variant_id' => $variant ? $variant->id : null,
                        'product_name' => $product->name,
                        'variant_name' => $variantName,
                        'sku' => $sku,
                        'unit_price' => $unitPrice,
                        'quantity' => $item->quantity,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                $grandTotal = $subtotal + $deliveryCharge;

                do {
                    $orderNumber = 'KB-' . rand(1000, 9999);
                } while (Order::where('order_number', $orderNumber)->exists());

                $courierName = null;
                $courierServiceId = null;
                if ($request->filled('courier_service_id')) {
                    $cService = CourierService::find($request->courier_service_id);
                    if ($cService) {
                        $courierServiceId = $cService->id;
                        $courierName = $cService->name;
                    }
                }

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->check() ? auth()->id() : null,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_email' => $request->customer_email,
                    'shipping_district' => $request->shipping_district,
                    'shipping_upazila' => $request->shipping_upazila,
                    'shipping_address' => $request->shipping_address,
                    'delivery_area' => $request->delivery_area,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'delivery_charge' => $deliveryCharge,
                    'grand_total' => $grandTotal,
                    'payment_method' => $request->payment_method,
                    'courier_service_id' => $courierServiceId,
                    'courier_name' => $courierName,
                    'sender_number' => $request->sender_number,
                    'transaction_id' => $request->transaction_id,
                    'payment_screenshot' => $screenshotPath,
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'notes' => $request->notes,
                ]);

                foreach ($orderItemsData as $itemData) {
                    $itemData['order_id'] = $order->id;
                    OrderItem::create($itemData);
                }

                // Clear cart
                $cart->items()->delete();

                return $order;
            });

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'অর্ডার সম্পন্ন করার সময় সমস্যা দেখা দিয়েছে: ' . $e->getMessage());
        }
    }

    public function success($orderNumber)
    {
        $order = Order::with(['items', 'messages'])->where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.checkout_success', compact('order'));
    }
}
