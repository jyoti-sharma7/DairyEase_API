<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkDetail extends Model
{
    use HasFactory;
    protected $table ="milk_details";
    protected $fillable = ['user_id', 'shift', 'date', 'per_fat_amt', 'fat_rate', 'per_snf_amt', 'snf_rate', 'liter'];

    protected $appends = ['total_fat', 'total_snf','per_liter_amt','balance'];

    public function getTotalFatAttribute()
    {
        return $this->per_fat_amt * $this->fat_rate;
    }

    public function getTotalSnfAttribute()
    {
        return $this->per_snf_amt * $this->snf_rate;
    }
    public function getPerLiterAmtAttribute()
    {
        return $this->total_fat + $this->total_snf;
    }
    public function getBalanceAttribute()
    {
        return $this->per_liter_amt * $this->liter;
    }
 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
