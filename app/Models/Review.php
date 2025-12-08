<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'rent_id',
        'product_id',
        'rating',
        'comment',
        'image',
        'is_approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    // Get transaction (order atau rent)
    public function transaction()
    {
        return $this->order_id ? $this->order : $this->rent;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    // Cek apakah user sudah mereview produk dalam transaksi ini
    public static function hasReviewed($userId, $productId, $orderId = null, $rentId = null)
    {
        if ($orderId) {
            return self::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('order_id', $orderId)
                ->exists();
        }
        
        if ($rentId) {
            return self::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('rent_id', $rentId)
                ->exists();
        }
        
        return false;
    }
}