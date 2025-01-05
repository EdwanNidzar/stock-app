<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'photo',
        'expired_at',
        'note'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPhotoUrlAttribute()
    {
        return asset('storage/' . $this->photo);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
