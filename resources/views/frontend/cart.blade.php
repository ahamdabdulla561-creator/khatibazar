@extends('layouts.app')

@section('title', 'Your Cart - Khati Bajar')

@section('content')
<div class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6">Your Shopping Cart</h1>

        @if($cart && $cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Cart Items Table -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart->items as $item)
                        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4" x-data="{ qty: {{ $item->quantity }}, subtotal: '{{ format_price($item->price * $item->quantity) }}' }">
                            
                            <!-- Product Details -->
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100x100?text=KB' }}" class="w-16 h-16 object-cover rounded-xl border border-gray-100 shrink-0">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-sm md:text-base line-clamp-1">
                                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                    </h3>
                                    @if($item->variant)
                                        <span class="inline-block bg-brand-50 text-brand-700 text-xs font-semibold px-2.5 py-0.5 rounded-full mt-1">
                                            {{ $item->variant->name }}
                                        </span>
                                    @endif
                                    <p class="text-xs text-brand-600 font-bold mt-1">{{ format_price($item->price) }} / per unit</p>
                                </div>
                            </div>

                            <!-- Quantity Modifier & Price Subtotal -->
                            <div class="flex items-center justify-between w-full sm:w-auto gap-6">
                                <!-- Qty selector -->
                                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                    <button 
                                        type="button" 
                                        @click="if(qty > 1) { qty--; updateQty({{ $item->id }}, qty); }"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 font-bold text-xs"
                                    >-</button>
                                    <span class="w-10 text-center font-bold text-xs" x-text="qty"></span>
                                    <button 
                                        type="button" 
                                        @click="qty++; updateQty({{ $item->id }}, qty);"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 font-bold text-xs"
                                    >+</button>
                                </div>

                                <!-- Item Subtotal -->
                                <div class="text-right min-w-[90px]">
                                    <span class="font-extrabold text-gray-900 text-sm md:text-base" x-text="subtotal"></span>
                                </div>

                                <!-- Remove Button -->
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 text-base transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary Sidebar -->
                <div>
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm sticky top-24">
                        <h2 class="font-bold text-gray-900 text-lg mb-4 pb-2 border-b">Order Summary</h2>

                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal:</span>
                                <span class="font-bold text-gray-900">{{ format_price($cart->items->sum(fn($i) => $i->price * $i->quantity)) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Delivery Charge:</span>
                                <span class="text-xs text-brand-600 font-semibold">Added at checkout</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-base font-extrabold text-gray-900">
                                <span>Total (Grand Total):</span>
                                <span class="text-brand-700 text-lg">{{ format_price($cart->items->sum(fn($i) => $i->price * $i->quantity)) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-3.5 rounded-2xl shadow-lg hover:shadow-brand-600/30 transition text-center block text-sm">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>

                        <a href="{{ route('products.index') }}" class="block text-center text-xs text-gray-500 font-semibold hover:underline mt-4">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <script>
                function updateQty(itemId, newQty) {
                    fetch('/cart/update/' + itemId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ quantity: newQty })
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            location.reload();
                        }
                    });
                }
            </script>

        @else
            <!-- Empty Cart State -->
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm max-w-lg mx-auto my-12">
                <div class="w-24 h-24 bg-brand-50 text-brand-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty!</h2>
                <p class="text-sm text-gray-500 mb-8 leading-relaxed">No products found in your cart. Visit our shop page to browse farm and agricultural products.</p>
                <a href="{{ route('products.index') }}" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-8 py-3.5 rounded-full shadow-lg transition inline-block text-sm">
                    Browse Products
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
