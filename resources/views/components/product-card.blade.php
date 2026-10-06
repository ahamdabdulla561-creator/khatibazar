@props(['product'])

<div class="product-card-anim product-card-hover bg-white rounded-2xl border border-gray-100/80 shadow-sm flex flex-col justify-between overflow-hidden group relative" x-data="productCard({{ $product->id }}, {{ json_encode($product->variants) }})">
    
    <!-- Badges -->
    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
        @if($product->discount_percent > 0)
            <span class="animate-discount-glow bg-red-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm inline-block">
                {{ $product->discount_percent }}% Off
            </span>
        @endif
        @if($product->is_super_offer)
            <span class="animate-badge-glow bg-amber-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm inline-block">
                Super Offer
            </span>
        @endif
    </div>

    <!-- Product Image -->
    <div class="relative bg-gray-50 overflow-hidden pt-[100%]">
        <a href="{{ route('products.show', $product->slug) }}">
            <img 
                src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400?text=Khati+Bajar' }}" 
                alt="{{ $product->name }}" 
                class="absolute inset-0 w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out"
            >
        </a>
        <!-- Quick View Overlay Button (4️⃣ Star Highlight) -->
        <button 
            type="button" 
            @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
            class="absolute inset-x-3 bottom-3 bg-white/95 backdrop-blur-sm text-slate-900 font-extrabold text-xs py-2 px-3 rounded-xl shadow-lg border border-gray-200 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 flex items-center justify-center gap-1.5 hover:bg-brand-600 hover:text-white hover:border-brand-600"
        >
            <i class="fa-solid fa-eye"></i> Quick View
        </button>
    </div>

    <!-- Content -->
    <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
            @if($product->category)
                <span class="text-[11px] font-semibold text-brand-600 uppercase tracking-wider block mb-1">
                    {{ $product->category->name }}
                </span>
            @endif

            <h3 class="font-bold text-gray-900 text-sm md:text-base line-clamp-2 hover:text-brand-600 transition mb-2">
                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
            </h3>

            <!-- Variants pills selector if product has variants -->
            @if($product->variants && count($product->variants) > 0)
                <div class="mb-3">
                    <label class="block text-[11px] text-gray-500 font-medium mb-1">Select Size/Weight:</label>
                    <select x-model="selectedVariantId" @change="updateVariantPrice()" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-lg p-1.5 focus:ring-1 focus:ring-brand-500">
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->name }} — {{ format_price($variant->price) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div>
            <!-- Price Display -->
            <div class="flex items-baseline gap-2 mb-3">
                <span class="text-base md:text-lg font-bold text-brand-700" x-text="priceFormatted">
                    {{ format_price($product->effective_price) }}
                </span>
                @if($product->sale_price && $product->sale_price < $product->regular_price)
                    <span class="text-xs text-gray-400 line-through">
                        {{ format_price($product->regular_price) }}
                    </span>
                @endif
            </div>

            <!-- Action Buttons -->
            @if($product->inStock())
                <div class="grid grid-cols-2 gap-2">
                    <button 
                        type="button" 
                        @click="addToCart(false)" 
                        class="bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs md:text-sm font-semibold py-2 px-2 rounded-xl transition text-center flex items-center justify-center gap-1 border border-brand-200"
                    >
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                    <button 
                        type="button" 
                        @click="addToCart(true)" 
                        class="bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-bold py-2 px-2 rounded-xl transition text-center shadow-md flex items-center justify-center gap-1"
                    >
                        <i class="fa-solid fa-bolt"></i> Order Now
                    </button>
                </div>
            @else
                <button disabled class="w-full bg-gray-200 text-gray-500 text-xs md:text-sm font-bold py-2 rounded-xl cursor-not-allowed">
                    Out of Stock
                </button>
            @endif
        </div>
    </div>
</div>

<script>
    function productCard(productId, variants) {
        return {
            productId: productId,
            variants: variants || [],
            selectedVariantId: (variants && variants.length > 0) ? variants[0].id : null,
            priceFormatted: '{{ format_price($product->effective_price) }}',

            updateVariantPrice() {
                if (this.selectedVariantId && this.variants.length > 0) {
                    let found = this.variants.find(v => v.id == this.selectedVariantId);
                    if (found) {
                        this.priceFormatted = '৳ ' + Number(found.price).toLocaleString();
                    }
                }
            },

            addToCart(buyNow = false) {
                let payload = {
                    product_id: this.productId,
                    variant_id: this.selectedVariantId,
                    quantity: 1,
                    _token: '{{ csrf_token() }}'
                };

                fetch('{{ route("cart.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.dispatchEvent(new CustomEvent('cart-updated', { detail: data.cart_count }));
                        if (buyNow) {
                            window.location.href = '{{ route("checkout.index") }}';
                        } else {
                            alert(data.message);
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'Something went wrong');
                    }
                })
                .catch(err => alert('An error occurred while adding to cart.'));
            }
        }
    }
</script>
