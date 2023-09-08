<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'product',
        'shift',
        'quantity',
        'unit',
        'per_quantity',
    ];

    protected $appends = ['total_price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->per_quantity * $this->unit;
    }
}
