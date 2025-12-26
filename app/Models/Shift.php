<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    protected $fillable = ['branch_id', 'shift_day', 'start_time', 'end_time', 'attendance_data'];

    // Cast JSON otomatis menjadi array PHP
    protected $casts = [
        'attendance_data' => 'array',
        'shift_day' => 'date'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}