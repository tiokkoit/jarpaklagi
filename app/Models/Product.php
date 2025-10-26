<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'image',
        'hpp',
        'stock',
    ];
    // Accessor buat gambar biar bisa dipanggil dari Filament / view
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function packages()
    {
        return $this->hasMany(ProductPackage::class);
    }
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
