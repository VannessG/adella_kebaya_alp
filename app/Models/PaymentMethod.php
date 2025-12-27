<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'account_name',
        'account_number',
        'qr_code_image',
        'is_active'
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}