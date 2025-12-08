<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rajaongkir_cache', function (Blueprint $table) {
            $table->id();
            $table->string('cache_key')->unique();
            $table->longText('cache_value');
            $table->timestamp('cached_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rajaongkir_cache');
    }
};