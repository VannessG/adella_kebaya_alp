<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('rent_number')->unique();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('total_days');
            $table->enum('status', ['pending', 'payment_check', 'confirmed', 'active', 'returned', 'completed', 'cancelled', 'overdue'])->default('pending');
            $table->string('payment_proof')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0); // Harga asli produk x hari
            $table->decimal('discount_amount', 15, 2)->default(0); // Nominal potongan
            $table->decimal('shipping_cost', 15, 2)->default(0); // Ongkir
            $table->decimal('total_amount', 15, 2); // Hasil akhir (Subtotal - Diskon + Ongkir)
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('delivery_type', ['pickup', 'delivery'])->default('pickup');
            $table->text('customer_address')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};