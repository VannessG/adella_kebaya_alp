<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = ['branch_id', 'name', 'nik', 'address', 'phone', 'is_active'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}