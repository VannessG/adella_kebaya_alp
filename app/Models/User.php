<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'branch_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array{
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function rents(){
        return $this->hasMany(Rent::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function isAdmin(){
        return $this->role === 'admin';
    }
}