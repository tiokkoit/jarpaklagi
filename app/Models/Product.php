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

    // ⭐ KODE PERBAIKAN DIMULAI DI SINI ⭐
    protected static function booted()
    {
        // Event ini dipicu tepat sebelum operasi DELETE dijalankan
        static::deleting(function ($product) {
            
            // Hapus semua data StockMovement yang memiliki product_id dari produk yang akan dihapus.
            // Ini membersihkan "anak" sebelum menghapus "induk".
            $product->stockMovements()->delete();
            
            // Catatan: Jika ada tabel lain (misalnya 'order_items') yang 
            // memiliki foreign key restrict ke 'products', Anda juga harus 
            // menambahkan baris untuk menghapus atau mengupdate data tersebut di sini.
        });
    }
    // ⭐ KODE PERBAIKAN SELESAI ⭐
}
