<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\ComboOffer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // Target the 4 seeded initial products from the homepage
            $targetSlugs = [
                'cfc-plus-combo-feed-concentrate',
                'super-vet-calcium-mineral-solution',
                'bioaqua-fish-growth-booster-500g',
                'organic-farm-soil-enhancer-5kg',
            ];

            $products = Product::whereIn('slug', $targetSlugs)->get();

            foreach ($products as $p) {
                // Detach from combo offers
                ComboOffer::where('product_id', $p->id)->update(['product_id' => null]);

                // Detach order items
                $variantIds = ProductVariant::where('product_id', $p->id)->pluck('id')->toArray();
                if (!empty($variantIds)) {
                    OrderItem::whereIn('product_variant_id', $variantIds)->update(['product_variant_id' => null]);
                }
                OrderItem::where('product_id', $p->id)->update(['product_id' => null]);

                // Delete cart items
                CartItem::where('product_id', $p->id)->delete();

                // Delete variants & images
                ProductVariant::where('product_id', $p->id)->delete();
                ProductImage::where('product_id', $p->id)->delete();

                // Delete product
                $p->delete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
