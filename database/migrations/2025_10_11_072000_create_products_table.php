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
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('rent_price_per_day', 10, 2)->nullable();
            $table->integer('min_rent_days')->default(1);
            $table->integer('max_rent_days')->default(30);
            $table->string('image');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description');
            $table->integer('stock')->default(0);
            $table->integer('weight')->default(0);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_available_for_rent')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};