<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('regular_price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('discount_percent')->nullable();
            $table->integer('stock')->default(0);
            $table->string('status')->default('active'); // active, inactive
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_super_offer')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'is_featured', 'is_super_offer']);
            $table->index('regular_price');
            $table->index('sale_price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
