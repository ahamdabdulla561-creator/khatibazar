<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart()
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function index()
    {
        $cart = $this->getCart();
        $cart->load(['items.product', 'items.variant']);

        return view('frontend.cart', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;

        if ($request->variant_id) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('id', $request->variant_id)
                ->firstOrFail();

            if ($variant->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "দুঃখিত, নির্বাচিত ভ্যারিয়েন্টের মাত্র {$variant->stock} টি স্টক রয়েছে।",
                ], 422);
            }
            $price = $variant->price;
        } else {
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "দুঃখিত, পণ্যটির মাত্র {$product->stock} টি স্টক রয়েছে।",
                ], 422);
            }
            $price = $product->effective_price;
        }

        $cart = $this->getCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant ? $variant->id : null)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $request->quantity;
            $maxStock = $variant ? $variant->stock : $product->stock;
            if ($newQty > $maxStock) {
                $newQty = $maxStock;
            }
            $cartItem->update([
                'quantity' => $newQty,
                'price' => $price,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant ? $variant->id : null,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        $cartCount = $cart->items()->sum('quantity');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'পণ্যটি আপনার কার্টে যুক্ত করা হয়েছে!',
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->back()->with('success', 'পণ্যটি আপনার কার্টে যুক্ত করা হয়েছে!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();
        $item = CartItem::where('cart_id', $cart->id)->where('id', $id)->firstOrFail();

        $maxStock = $item->variant ? $item->variant->stock : $item->product->stock;
        $qty = min($request->quantity, $maxStock);

        $item->update(['quantity' => $qty]);

        $cart->load(['items.product', 'items.variant']);
        $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
        $cartCount = $cart->items->sum('quantity');

        return response()->json([
            'success' => true,
            'item_subtotal' => format_price($item->price * $item->quantity),
            'cart_subtotal' => format_price($subtotal),
            'cart_count' => $cartCount,
            'quantity' => $qty,
        ]);
    }

    public function destroy($id)
    {
        $cart = $this->getCart();
        $item = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();
        if ($item) {
            $item->delete();
        }

        if (request()->wantsJson()) {
            $cart->load(['items.product', 'items.variant']);
            $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
            $cartCount = $cart->items->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'আইটেমটি কার্ট থেকে সরিয়ে নেওয়া হয়েছে।',
                'cart_subtotal' => format_price($subtotal),
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->back()->with('success', 'আইটেমটি কার্ট থেকে সরিয়ে নেওয়া হয়েছে।');
    }

    public function count()
    {
        $cart = $this->getCart();
        $count = $cart->items()->sum('quantity');
        $subtotal = $cart->items->get()->sum(fn($i) => $i->price * $i->quantity);

        return response()->json([
            'count' => $count,
            'subtotal_formatted' => format_price($subtotal),
        ]);
    }
}
