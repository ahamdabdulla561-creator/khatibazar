<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderMessage;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $orders = collect();
        $searched = false;

        if ($request->filled('query')) {
            $searched = true;
            $q = trim($request->get('query'));
            $cleanQ = ltrim($q, '#');
            $digitsOnly = preg_replace('/[^0-9]/', '', $q);
            
            $orders = Order::with(['items', 'messages'])
                ->where('order_number', $q)
                ->orWhere('order_number', $cleanQ)
                ->orWhere('order_number', 'KB-' . $cleanQ)
                ->orWhere('order_number', 'KB-' . $digitsOnly)
                ->orWhere('customer_phone', $q)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('frontend.track_order', compact('orders', 'searched'));
    }

    public function sendOrderMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($id);

        OrderMessage::create([
            'order_id' => $order->id,
            'sender_type' => 'customer',
            'sender_name' => auth()->check() ? auth()->user()->name : $order->customer_name,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'আপনার মেসেজ সফলভাবে পাঠানো হয়েছে!');
    }
}
