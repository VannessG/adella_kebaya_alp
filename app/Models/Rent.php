<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rent extends Model{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'rent_number', 
        'start_date', 
        'end_date', 
        'total_days',
        'status', 
        'subtotal', 
        'discount_amount', 
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
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'rent_product')
                    ->withPivot('quantity', 'price_per_day', 'subtotal')
                    ->withTimestamps();
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
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
            'confirmed' => 'Terkonfirmasi',
            'active' => 'Sedang Disewa',
            'returned' => 'Dikembalikan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'overdue' => 'Terlambat'
        ];
    }

    public function getStatusStyleAttribute(){
        $style = [
            'bg' => '#fff',
            'color' => '#000',
            'border' => '1px solid #000',
            'label' => strtoupper($this->status)
        ];

        switch ($this->status) {
            case 'payment_check':
                $style = ['bg' => '#ffc107', 'color' => '#000', 'border' => 'none', 'label' => 'CEK BAYAR'];
                break;
            case 'pending':
                $style = ['bg' => '#fff', 'color' => '#666', 'border' => '1px solid #ccc', 'label' => 'BELUM BAYAR'];
                break;
            case 'confirmed':
                $style = ['bg' => '#17a2b8', 'color' => '#fff', 'border' => 'none', 'label' => 'TERKONFIRMASI'];
                break;
            case 'active':
                $style = ['bg' => '#28a745', 'color' => '#fff', 'border' => 'none', 'label' => 'SEDANG DISEWA'];
                break;
            case 'returned':
                $style = ['bg' => '#6c757d', 'color' => '#fff', 'border' => 'none', 'label' => 'DIKEMBALIKAN'];
                break;
            case 'completed':
                $style = ['bg' => '#000', 'color' => '#fff', 'border' => 'none', 'label' => 'SELESAI'];
                break;
            case 'overdue':
                $style = ['bg' => '#dc3545', 'color' => '#fff', 'border' => 'none', 'label' => 'TERLAMBAT'];
                break;
            case 'cancelled':
                $style = ['bg' => '#fff', 'color' => '#dc3545', 'border' => '1px solid #dc3545', 'label' => 'DIBATALKAN'];
                break;
        }
        return $style;
    }

    public function canBeCancelled(){
        return in_array($this->status, ['pending', 'payment_check']);
    }

    public function canBeReturned(){
        return $this->status === 'active' && now()->greaterThanOrEqualTo($this->end_date);
    }

    public function calculatePenalty(){
        if ($this->status === 'overdue') {
            $overdueDays = now()->diffInDays($this->end_date);
            return $this->total_amount * 0.1 * $overdueDays;
        }
        return 0;
    }

    public function calculateRentalDays(){
        if (!$this->start_date || !$this->end_date) return 0;
        return $this->start_date->diffInDays($this->end_date);
    }

    public function payment(){
        return $this->morphOne(Payment::class, 'transaction');
    }
}