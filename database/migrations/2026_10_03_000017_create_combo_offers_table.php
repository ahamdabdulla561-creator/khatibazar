<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('combo_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('badge_text')->default('BIG COMBO OFFER');
            $table->string('image')->nullable();
            $table->string('offer_badge_text')->default('BIG OFFER');
            $table->string('offer_text')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('link')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_offers');
    }
};
