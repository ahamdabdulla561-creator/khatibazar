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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('courier_service_id')->nullable()->after('payment_method')->constrained('courier_services')->nullOnDelete();
            $table->string('courier_name')->nullable()->after('courier_service_id');
            $table->string('courier_tracking_id')->nullable()->after('courier_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['courier_service_id']);
            $table->dropColumn(['courier_service_id', 'courier_name', 'courier_tracking_id']);
        });
    }
};
