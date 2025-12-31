<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model{
    use HasFactory;

    protected $fillable = [
        'order_number', 
        'user_id', 
        'discount_id', 
        'branch_id',
        'customer_name', 
        'customer_phone', 
        'customer_address', 
        'status', 
        'order_date', 
        'subtotal', 
        'discount_amount',
        'total_amount', 
        'shipping_cost', 
        'delivery_type',
        'payment_proof'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2'
    ];

    // Tambahkan relasi payment tunggal (MorphOne)
    public function payment() {
        return $this->morphOne(Payment::class, 'transaction');
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity', 'price');
    }

    public function payments(){
        return $this->morphMany(Payment::class, 'transaction');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    // Relasi untuk mengecek apakah user sudah me-review produk tertentu di order ini
    public function userProductReview($productId){
        if (!Auth::check()) {
            return null;
        }

        return $this->reviews()
            ->where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();
    }

    public static function getStatusOptions(){
        return [
            'pending' => 'Menunggu Pembayaran',
            'payment_check' => 'Pengecekan Pembayaran',
            'processing' => 'Sedang Diproses',
            'shipping' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    }

    public static function dataWithOrderNumber($orderNumber){
        return self::with('products')
            ->where('order_number', $orderNumber)
            ->first();
    }

    public function canBeCancelled(){
        return in_array($this->status, ['pending', 'payment_check']);
    }

    public function canBeCompleted(){
        return $this->status === 'shipping';
    }

    public function getTotalWithShippingAttribute(){
        return $this->total_amount + $this->shipping_cost;
    }
}