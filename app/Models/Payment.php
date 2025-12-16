<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'transaction_type',
        'transaction_id',
        'payment_method_id',
        'amount',
        'payer_name',
        'payer_email',
        'payer_phone',
        'proof_image',
        'status',
        'notes',
        'snap_token',
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function transaction()
    {
        return $this->morphTo();
    }

    public function logs()
    {
        return $this->hasMany(PaymentLog::class, 'transaction_id', 'payment_number');
    }
}