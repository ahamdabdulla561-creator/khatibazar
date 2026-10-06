<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('shipping_district');
            $table->string('shipping_upazila');
            $table->text('shipping_address');
            $table->string('delivery_area')->default('inside_dhaka'); // inside_dhaka, outside_dhaka
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->string('payment_method')->default('cod'); // cod, bkash, nagad, rocket, sslcommerz
            $table->string('payment_status')->default('pending'); // pending, paid, unpaid, refunded
            $table->string('order_status')->default('pending'); // pending, confirmed, processing, shipped, delivered, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_status', 'payment_status']);
            $table->index('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
