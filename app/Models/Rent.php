<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rent_number',
        'start_date',
        'end_date',
        'total_days',
        'status',
        'total_amount',
        'branch_id',
        'delivery_type',
        'shipping_cost',
        'customer_address',
        'customer_name',
        'customer_phone',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'rent_product')
                    ->withPivot('quantity', 'price_per_day', 'subtotal')
                    ->withTimestamps();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'transaction');
    }

    public function shipment()
    {
        return $this->morphOne(Shipment::class, 'transaction');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi untuk mengecek apakah user sudah me-review produk tertentu di order ini
    public function userProductReview($productId)
    {
        // Cek apakah user sedang login untuk menghindari error pada tamu (guest)
        if (!Auth::check()) {
            return null;
        }

        return $this->reviews()
            ->where('user_id', Auth::id()) // Lebih aman dan efisien
            ->where('product_id', $productId)
            ->first();
    }

    public static function getStatusOptions()
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'payment_check' => 'Pengecekan Pembayaran',
            'confirmed' => 'Terkonfirmasi',
            'active' => 'Sedang Disewa',
            'returned' => 'Dikembalikan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'overdue' => 'Terlambat'
        ];
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'payment_check']);
    }

    public function canBeReturned()
    {
        return $this->status === 'active' && now()->greaterThanOrEqualTo($this->end_date);
    }

    public function calculatePenalty()
    {
        if ($this->status === 'overdue') {
            $overdueDays = now()->diffInDays($this->end_date);
            return $this->total_amount * 0.1 * $overdueDays; // 10% per hari
        }
        return 0;
    }

    public function calculateRentalDays()
    {
        if (!$this->start_date || !$this->end_date) return 0;
        return $this->start_date->diffInDays($this->end_date);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'transaction');
    }
}