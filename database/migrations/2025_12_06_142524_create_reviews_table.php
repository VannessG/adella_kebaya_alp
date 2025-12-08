<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Hanya salah satu yang diisi (order ATAU rent)
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('rent_id')->nullable()->constrained()->onDelete('cascade');
            
            // Produk yang direview (wajib)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->integer('rating')->default(5);
            $table->text('comment');
            $table->string('image')->nullable();
            $table->boolean('is_approved')->default(true);
            
            // Unique constraint: 1 user hanya bisa review 1x per transaksi
            $table->unique(['user_id', 'order_id', 'product_id'])->whereNotNull('order_id');
            $table->unique(['user_id', 'rent_id', 'product_id'])->whereNotNull('rent_id');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};