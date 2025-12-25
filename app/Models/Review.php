<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'order_id', 'rent_id', 'product_id', 
        'rating', 'comment', 'image', 'is_approved'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    // Accessor: Sensor email menjadi u*******@gmail.com
    public function getMaskedEmailAttribute()
    {
        $email = $this->user->email;
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        
        $maskedName = substr($name, 0, 1) . str_repeat('*', 7);
        return $maskedName . '@' . $domain;
    }
}