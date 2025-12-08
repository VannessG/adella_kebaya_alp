<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type'); // 'order', 'rent'
            $table->unsignedBigInteger('transaction_id');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->string('courier_service')->nullable(); // 'gojek', 'jne', 'tiki'
            $table->string('service_type')->nullable(); // 'instant', 'same_day', 'regular'
            $table->string('tracking_number')->nullable();
            $table->enum('status', ['pending', 'driver_assigned', 'picked_up', 'on_delivery', 'delivered', 'cancelled'])->default('pending');
            $table->text('address_origin');
            $table->text('address_destination');
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('estimated_days')->nullable();
            $table->dateTime('pickup_time')->nullable();
            $table->dateTime('delivered_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};