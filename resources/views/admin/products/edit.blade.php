@extends('layouts.admin')

@section('title', 'Edit Product - Khati Bajar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="productForm({{ json_encode($product->variants) }})">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-900">Update Product</h1>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-gray-500 hover:underline">Back to List</a>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Product Info -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Basic Info</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Brand</label>
                    <select name="brand_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Main Product Stock <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>
            </div>
        </div>

        <!-- Pricing Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Pricing</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Regular Price (৳) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="regular_price" value="{{ old('regular_price', $product->regular_price) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Sale Price (৳)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>
            </div>
        </div>

        <!-- Variants Manager Card -->
        <!-- Variants Section (Size & Color Support) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Product Variants (Size & Color System)</h2>
                    <p class="text-xs text-gray-500">Manage variations like Weight/Size (e.g. 500g, 1 KG, 5 KG, L, XL) and Color (e.g. Red, Blue)</p>
                </div>
                <button type="button" @click="addVariant()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1">
                    <i class="fa-solid fa-plus"></i> Add Size/Color Variant
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(v, index) in variants" :key="index">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                        <input type="hidden" :name="'variants['+index+'][id]'" x-model="v.id">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Variant Name</label>
                            <input type="text" :name="'variants['+index+'][name]'" x-model="v.name" placeholder="e.g. 1 KG Red" class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Size / Weight</label>
                            <input type="text" :name="'variants['+index+'][size]'" x-model="v.size" placeholder="e.g. 1 KG / XL" class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Color</label>
                            <input type="text" :name="'variants['+index+'][color]'" x-model="v.color" placeholder="e.g. Red / Blue" class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Price (৳)</label>
                            <input type="number" step="0.01" :name="'variants['+index+'][price]'" x-model="v.price" required class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Stock</label>
                            <input type="number" :name="'variants['+index+'][stock]'" x-model="v.stock" required class="w-full bg-white border border-gray-200 rounded-xl p-2 text-xs">
                        </div>
                        <div class="flex items-center justify-between">
                            <input type="text" :name="'variants['+index+'][sku]'" x-model="v.sku" placeholder="SKU" class="w-3/4 bg-white border border-gray-200 rounded-xl p-2 text-xs">
                            <button type="button" @click="removeVariant(index)" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Description & Images -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Images & Description</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Upload New Main Image (if any)</label>
                    @if($product->image)
                        <img src="{{ $product->image_url }}" class="w-16 h-16 object-cover rounded-xl border mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2 bg-gray-50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Upload Additional Gallery Images</label>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2 bg-gray-50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Full Description</label>
                <textarea name="description" rows="5" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="pt-4 border-t">
                <div class="max-w-xs">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-4 rounded-2xl shadow-xl transition text-base">
            Save Updates
        </button>
    </form>
</div>

<script>
    function productForm(initialVariants) {
        return {
            variants: initialVariants || [],
            addVariant() {
                this.variants.push({ id: null, name: '', price: '', stock: 50, sku: '' });
            },
            removeVariant(idx) {
                this.variants.splice(idx, 1);
            }
        }
    }
</script>
@endsection
