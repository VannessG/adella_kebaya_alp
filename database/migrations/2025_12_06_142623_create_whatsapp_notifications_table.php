<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('message');
            $table->string('type'); // 'order', 'rent', 'payment', 'shipment'
            $table->unsignedBigInteger('reference_id');
            $table->string('status'); // 'pending', 'sent', 'failed'
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notifications');
    }
};