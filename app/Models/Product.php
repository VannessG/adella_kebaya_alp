<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'price', 
        'rent_price_per_day',
        'min_rent_days',
        'max_rent_days',
        'image', 
        'category_id', 
        'branch_id',
        'description', 
        'stock', 
        'weight',
        'is_available',
        'is_available_for_rent'
    ];

    protected $appends = ['image_url', 'discounted_price', 'is_discounted'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }
        return asset('images/logo.png');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('quantity', 'price');
    }

    public function rents(){
        return $this->belongsToMany(Rent::class, 'rent_product')
                    ->withPivot('quantity', 'price_per_day', 'subtotal');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function getDiscountedPriceAttribute()
    {
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if ($activeDiscount) {
            return $this->price - $activeDiscount->applyTo($this->price);
        }

        return $this->price;
    }

    public function getIsDiscountedAttribute()
    {
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        return $activeDiscount !== null;
    }

    public function calculateRentPrice($days)
    {
        $days = max($this->min_rent_days, min($days, $this->max_rent_days));
        return $this->rent_price_per_day * $days;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}