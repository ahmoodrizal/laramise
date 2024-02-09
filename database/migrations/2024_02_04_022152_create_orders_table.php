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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_number');
            $table->unsignedInteger('subtotal');
            $table->string('shipping_service');
            $table->unsignedInteger('shipping_cost');
            $table->string('shipping_resi')->nullable();
            $table->unsignedInteger('total_cost');
            $table->enum('payment_method', ['bank_transfer', 'e_wallet', 'cash_on_delivery']);
            $table->string('payment_va_name')->nullable();
            $table->string('payment_va_number')->nullable();
            $table->string('payment_url')->nullable();
            $table->enum('status', ['pending', 'paid', 'on_delivery', 'delivered', 'expired', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
