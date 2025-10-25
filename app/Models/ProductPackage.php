<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProductPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'code',
        'name',
        'pcs_per_package',
        'price',
        'is_active',
    ];

    /**
     * Relasi ke Product
     * Setiap paket pasti milik satu produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    

    // Accessor untuk menampilkan nama produk + paket
    public function getFullNameAttribute(): string
    {
        return $this->product->name . ' - ' . $this->name;
    }
}

