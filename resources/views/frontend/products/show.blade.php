@extends('layouts.app')

@section('title', $product->name . ' - Khati Bazar')

@section('content')
<div class="bg-gray-50 py-8" x-data="productDetail({{ json_encode($product) }}, {{ json_encode($product->activeVariants) }})">
    <div class="max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex text-xs md:text-sm text-gray-500 mb-6 gap-2">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-brand-600">Products</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold truncate">{{ $product->name }}</span>
        </nav>

        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            
            <!-- Product Image Lightbox / Gallery -->
            <div>
                <div class="relative bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 mb-4 pt-[100%]">
                    <img :src="activeImage" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover">
                </div>

                @if($product->images && count($product->images) > 0)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @php
                            $mainImgSrc = $product->image ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image)) : 'https://placehold.co/600x600?text=Khati+Bazar';
                        @endphp
                        <button type="button" @click="activeImage = '{{ $mainImgSrc }}'" class="w-16 h-16 rounded-xl border-2 overflow-hidden shrink-0" :class="activeImage == '{{ $mainImgSrc }}' ? 'border-brand-600' : 'border-transparent'">
                            <img src="{{ $mainImgSrc }}" class="w-full h-full object-cover">
                        </button>
                        @foreach($product->images as $img)
                            @php
                                $gImgSrc = filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path);
                            @endphp
                            <button type="button" @click="activeImage = '{{ $gImgSrc }}'" class="w-16 h-16 rounded-xl border-2 overflow-hidden shrink-0" :class="activeImage == '{{ $gImgSrc }}' ? 'border-brand-600' : 'border-transparent'">
                                <img src="{{ $gImgSrc }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info & Actions -->
            <div class="flex flex-col justify-between">
                <div>
                    <!-- Badges -->
                    <div class="flex items-center gap-2 mb-3">
                        @if($product->category)
                            <span class="bg-brand-50 text-brand-700 text-xs font-bold px-3 py-1 rounded-full">
                                {{ $product->category->name }}
                            </span>
                        @endif
                        @if($product->brand)
                            <span class="bg-brand-50 text-brand-700 text-xs font-bold px-3 py-1 rounded-full">
                                {{ $product->brand->name }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 leading-snug">
                        {{ $product->name }}
                    </h1>

                    <p class="text-xs text-gray-400 mb-4">SKU: <span class="font-mono text-gray-600" x-text="selectedSku"></span></p>

                    <!-- Price Box -->
                    <div class="bg-brand-50/50 border border-brand-100 p-4 rounded-2xl flex items-baseline gap-3 mb-6">
                        <span class="text-2xl md:text-3xl font-extrabold text-brand-700" x-text="'৳ ' + Number(currentPrice).toLocaleString()"></span>
                        @if($product->sale_price && $product->sale_price < $product->regular_price)
                            <span class="text-sm text-gray-400 line-through">
                                {{ format_price($product->regular_price) }}
                            </span>
                        @endif
                        <span class="ml-auto text-xs font-bold px-2.5 py-1 rounded-full" :class="currentStock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                            <span x-text="currentStock > 0 ? 'In Stock (' + currentStock + ' left)' : 'Out of Stock'"></span>
                        </span>
                    </div>

                    <!-- Short Description -->
                    @if($product->short_description)
                        <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <!-- Variant Selector Pills (Size & Color) -->
                    @if($product->activeVariants && count($product->activeVariants) > 0)
                        <div class="mb-6 space-y-3">
                            <label class="block text-sm font-bold text-gray-800">Select Variant / Size / Color:</label>
                            <div class="flex flex-wrap gap-2.5">
                                <template x-for="v in variants" :key="v.id">
                                    <button 
                                        type="button" 
                                        @click="selectVariant(v)"
                                        class="px-4 py-2.5 rounded-xl text-xs md:text-sm font-bold border-2 transition shadow-sm flex items-center gap-1.5"
                                        :class="selectedVariantId == v.id ? 'border-brand-600 bg-brand-600 text-white shadow-brand-500/20' : 'border-gray-200 bg-white text-gray-700 hover:border-brand-300'"
                                    >
                                        <span x-text="v.name || ((v.size ? 'Size: ' + v.size : '') + (v.color ? ' | Color: ' + v.color : ''))"></span>
                                        <template x-if="v.size && v.name !== v.size">
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-black/10" x-text="v.size"></span>
                                        </template>
                                        <template x-if="v.color">
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-black/10" x-text="v.color"></span>
                                        </template>
                                        <span class="ml-1 text-[11px] opacity-80" x-text="'(৳' + Number(v.price).toLocaleString() + ')'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    @endif

                    <!-- Quantity Counter -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Quantity:</label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden bg-gray-50">
                                <button type="button" @click="quantity > 1 ? quantity-- : null" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-200 font-bold">-</button>
                                <span class="w-12 text-center font-bold text-sm bg-white py-2" x-text="quantity"></span>
                                <button type="button" @click="quantity < currentStock ? quantity++ : alert('Maximum stock available: ' + currentStock)" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:bg-gray-200 font-bold">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div>
                    <template x-if="currentStock > 0">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button 
                                type="button" 
                                @click="addToCart(false)" 
                                class="bg-brand-50 hover:bg-brand-100 text-brand-800 text-base font-bold py-3.5 px-6 rounded-2xl border border-brand-300 transition flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-cart-plus text-lg"></i> Add to Cart
                            </button>
                            <button 
                                type="button" 
                                @click="addToCart(true)" 
                                class="bg-brand-600 hover:bg-brand-700 text-white text-base font-extrabold py-3.5 px-6 rounded-2xl shadow-lg hover:shadow-brand-600/30 transition flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-bolt text-lg text-white"></i> Buy Now
                            </button>
                        </div>
                    </template>

                    <template x-if="currentStock <= 0">
                        <button disabled class="w-full bg-gray-200 text-gray-500 font-bold py-4 rounded-2xl text-center cursor-not-allowed">
                            Sorry, Out of Stock
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Description Details Section -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm mb-12">
            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Product Description</h2>
            <div class="prose max-w-none text-sm text-gray-700 leading-relaxed space-y-4">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        <!-- Related Products Section -->
        @if(count($relatedProducts) > 0)
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6">Related Products</h2>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($relatedProducts as $relProduct)
                        <x-product-card :product="$relProduct" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    function productDetail(product, variants) {
        let initialVar = (variants && variants.length > 0) ? variants[0] : null;
        return {
            product: product,
            variants: variants || [],
            selectedVariantId: initialVar ? initialVar.id : null,
            currentPrice: initialVar ? initialVar.price : (product.sale_price || product.regular_price),
            currentStock: initialVar ? initialVar.stock : product.stock,
            selectedSku: initialVar ? (initialVar.sku || product.sku) : product.sku,
            quantity: 1,
            activeImage: '{{ $product->image ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image)) : 'https://placehold.co/600x600?text=Khati+Bazar' }}',

            selectVariant(v) {
                this.selectedVariantId = v.id;
                this.currentPrice = v.price;
                this.currentStock = v.stock;
                this.selectedSku = v.sku || this.product.sku;
                if (this.quantity > this.currentStock) {
                    this.quantity = Math.max(1, this.currentStock);
                }
            },

            addToCart(buyNow = false) {
                fetch('{{ route("cart.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: this.product.id,
                        variant_id: this.selectedVariantId,
                        quantity: this.quantity
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (buyNow) {
                            window.location.href = '{{ route("checkout.index") }}';
                        } else {
                            alert(data.message);
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                });
            }
        }
    }
</script>
@endsection
