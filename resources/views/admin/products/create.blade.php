@extends('layouts.admin')

@section('title', 'Add New Product - Khati Bajar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="productForm()">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-900">Create New Product</h1>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-gray-500 hover:underline">Back to List</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Product Information Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Basic Info</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. CFC Plus Combo Feed Concentrate" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Brand (Optional)</label>
                    <select name="brand_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">SKU Code (Leave blank to auto-generate)</label>
                    <input type="text" name="sku" placeholder="KB-PRD-XXXX" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Main Product Stock <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="50" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>
            </div>
        </div>

        <!-- Pricing Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Pricing</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Regular Price (৳) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="regular_price" required placeholder="800" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Sale Price / Offer Price (৳)</label>
                    <input type="number" step="0.01" name="sale_price" placeholder="750" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                </div>
            </div>
        </div>

        <!-- Dynamic Variants Builder Card (Size & Color Support) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Product Variants (Size & Color System)</h2>
                    <p class="text-xs text-gray-500">Add variations like Weight/Size (e.g. 500g, 1 KG, 5 KG, L, XL) and Color (e.g. Red, Blue, Green)</p>
                </div>
                <button type="button" @click="addVariant()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1">
                    <i class="fa-solid fa-plus"></i> Add Size/Color Variant
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(v, index) in variants" :key="index">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
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
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Stock Quantity</label>
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

        <!-- Media & Description Card -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-gray-900 pb-2 border-b">Images & Description</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Main Product Image <span class="text-red-500">*</span></label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2 bg-gray-50">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Gallery Images (Multiple Upload)</label>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2 bg-gray-50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Full Description</label>
                <textarea name="description" rows="5" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-800">
                        <input type="checkbox" name="is_featured" value="1" class="text-brand-600 rounded">
                        <span>Show in Featured Products</span>
                    </label>
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-800">
                        <input type="checkbox" name="is_super_offer" value="1" class="text-amber-500 rounded">
                        <span>Show in Super Offers</span>
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-extrabold py-4 rounded-2xl shadow-xl transition text-base">
            Save Product
        </button>
    </form>
</div>

<script>
    function productForm() {
        return {
            variants: [],
            addVariant() {
                this.variants.push({ name: '', price: '', stock: 50, sku: '' });
            },
            removeVariant(idx) {
                this.variants.splice(idx, 1);
            }
        }
    }
</script>
@endsection
