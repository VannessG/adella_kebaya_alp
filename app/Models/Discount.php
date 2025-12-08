<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'amount',
        'max_usage',
        'used_count',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function isActive()
    {
        return $this->is_active && 
               now()->between($this->start_date, $this->end_date) &&
               ($this->max_usage === null || $this->used_count < $this->max_usage);
    }

    public function applyTo($amount)
    {
        if ($this->type === 'percentage') {
            return $amount * ($this->amount / 100);
        }
        return min($this->amount, $amount);
    }
}