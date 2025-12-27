<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'is_active'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function rents(){
        return $this->hasMany(Rent::class);
    }
}