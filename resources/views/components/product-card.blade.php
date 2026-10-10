@props(['product'])

<div class="product-card-anim product-card-hover bg-white rounded-xl sm:rounded-2xl border border-brand-100/90 shadow-sm flex flex-col justify-between overflow-hidden group relative" x-data="productCard({{ $product->id }}, {{ json_encode($product->variants) }})">
    
    <!-- Product Image (Strict 1:1 Square) -->
    <div class="relative bg-gray-50/70 overflow-hidden pt-[100%]">
        <a href="{{ route('products.show', $product->slug) }}">
            <img 
                src="{{ $product->image_url }}" 
                alt="{{ $product->name }}" 
                class="absolute inset-0 w-full h-full object-contain p-1.5 sm:p-2.5 group-hover:scale-105 transition-transform duration-500 ease-out"
                loading="lazy"
            >
        </a>
        <!-- Quick View Overlay Button -->
        <button 
            type="button" 
            @click="$dispatch('open-quickview', { id: {{ $product->id }} })"
            class="hidden sm:flex absolute inset-x-3 bottom-3 bg-white/95 backdrop-blur-sm text-slate-900 font-extrabold text-xs py-2 px-3 rounded-xl shadow-lg border border-gray-200 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 items-center justify-center gap-1.5 hover:bg-brand-600 hover:text-white hover:border-brand-600"
        >
            <i class="fa-solid fa-eye"></i> Quick View
        </button>
    </div>

    <!-- Content -->
    <div class="p-2.5 sm:p-4 flex-1 flex flex-col justify-between border-t border-gray-50">
        <div class="flex-1">
            @if($product->category)
                <span class="text-[10px] sm:text-[11px] font-semibold text-brand-600 uppercase tracking-wider block mb-0.5 truncate">
                    {{ $product->category->name }}
                </span>
            @endif

            <h3 class="font-bold text-gray-900 text-xs sm:text-sm md:text-base line-clamp-2 hover:text-brand-600 transition mb-1.5 sm:mb-2 leading-snug min-h-[2.25rem] sm:min-h-[2.5rem]">
                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
            </h3>

            <!-- Variants selector if product has variants -->
            @if($product->variants && count($product->variants) > 0)
                <div class="mb-2 sm:mb-3">
                    <select x-model="selectedVariantId" @change="updateVariantPrice()" class="w-full text-[11px] sm:text-xs bg-gray-50 border border-gray-200 rounded-lg py-1 px-1.5 sm:p-1.5 focus:ring-1 focus:ring-brand-500 text-gray-700 font-medium">
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->name }} — {{ format_price($variant->price) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="mt-auto pt-1">
            <!-- Price Display -->
            <div class="flex items-baseline flex-wrap gap-1.5 sm:gap-2 mb-2.5 sm:mb-3">
                <span class="text-sm sm:text-base md:text-lg font-extrabold text-brand-700" x-text="priceFormatted">
                    {{ format_price($product->effective_price) }}
                </span>
                @if($product->sale_price && $product->sale_price < $product->regular_price)
                    <span class="text-[10px] sm:text-xs text-gray-400 line-through">
                        {{ format_price($product->regular_price) }}
                    </span>
                @endif
            </div>

            <!-- Action Buttons -->
            @if($product->inStock())
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2">
                    <button 
                        type="button" 
                        @click="addToCart(false)" 
                        class="bg-brand-50 hover:bg-brand-100 text-brand-700 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 px-1.5 sm:px-2 rounded-lg sm:rounded-xl transition text-center flex items-center justify-center gap-1 border border-brand-200"
                    >
                        <i class="fa-solid fa-cart-plus text-[10px] sm:text-xs"></i>
                        <span>Cart</span>
                    </button>
                    <button 
                        type="button" 
                        @click="addToCart(true)" 
                        class="bg-brand-600 hover:bg-brand-700 text-white text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 px-1.5 sm:px-2 rounded-lg sm:rounded-xl transition text-center shadow-sm flex items-center justify-center gap-1"
                    >
                        <i class="fa-solid fa-bolt text-[10px] sm:text-xs"></i>
                        <span>Buy Now</span>
                    </button>
                </div>
            @else
                <button disabled class="w-full bg-gray-100 text-gray-400 text-[11px] sm:text-xs font-bold py-1.5 sm:py-2 rounded-lg sm:rounded-xl cursor-not-allowed">
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
