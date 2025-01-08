<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'category_id',
        'supplier_id',
        'product_photo',
        'price',
        'stock',
        'unit',
        'threshold',
        'description',
    ];

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoryProduct::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function updateStock($type, $quantity, $note = null, $expiredAt = null, $photoPath = null, $userId = null)
    {
        // Update stok berdasarkan tipe
        if ($type === 'in') {
            $this->stock += $quantity;
        } elseif ($type === 'out') {
            $this->stock -= $quantity;
        }

        // Simpan perubahan stok ke dalam database
        $this->save();

        // Simpan log perubahan stok (jika perlu)
        $this->stockLogs()->create([
            'type' => $type,
            'quantity' => $quantity,
            'note' => $note,
            'expired_at' => $expiredAt,
            'photo' => $photoPath,
            'user_id' => $userId,
        ]);
    }


}
