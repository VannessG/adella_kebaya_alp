<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type',
        'transaction_id',
        'branch_id',
        'courier_service',
        'service_type',
        'tracking_number',
        'status',
        'address_origin',
        'address_destination',
        'cost',
        'distance_km',
        'estimated_days',
        'pickup_time',
        'delivered_time',
        'notes'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'pickup_time' => 'datetime',
        'delivered_time' => 'datetime'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transaction()
    {
        return $this->morphTo();
    }

    public static function getStatusOptions()
    {
        return [
            'pending' => 'Menunggu',
            'driver_assigned' => 'Driver Ditugaskan',
            'picked_up' => 'Barang Diambil',
            'on_delivery' => 'Dalam Pengiriman',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan'
        ];
    }
}