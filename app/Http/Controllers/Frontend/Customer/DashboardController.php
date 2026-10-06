<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $totalOrders = Order::where('user_id', $user->id)->count();
        $addresses = Address::where('user_id', $user->id)->get();

        return view('frontend.customer.dashboard', compact('user', 'recentOrders', 'totalOrders', 'addresses'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('frontend.customer.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::with(['items.product', 'messages'])
            ->where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        return view('frontend.customer.order_detail', compact('order'));
    }

    public function sendOrderMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order = Order::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        OrderMessage::create([
            'order_id' => $order->id,
            'sender_type' => 'customer',
            'sender_name' => auth()->user()->name ?? $order->customer_name,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'আপনার ইনবক্স বার্তা সফলভাবে পাঠানো হয়েছে!');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'আপনার প্রোফাইল তথ্য পরিবর্তন সফল হয়েছে!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'বর্তমান পাসওয়ার্ডটি সঠিক নয়।');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তিত হয়েছে!');
    }
}
