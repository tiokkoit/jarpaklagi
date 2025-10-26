<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\StockMovementService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     * Dipicu SETELAH produk disimpan di database.
     */
    public function created(Product $product): void
    {
        $initialStock = $product->stock; // Ambil nilai 'stock' yang dimasukkan di form Filament

        // Jika ada stok awal yang dimasukkan
        if ($initialStock > 0) {
            
            try {
                // 1. Reset stock snapshot di Product ke 0 SECARA LANGSUNG di database.
                // Ini penting agar Service yang baru kita panggil MENGHITUNG stock mulai dari 0.
                DB::table('products')->where('id', $product->id)->update(['stock' => 0]);
                
                // 2. Panggil Service. 
                // Service ini akan: Mencatat movement 'initial_stock' dan MENG-UPDATE product.stock dari 0 ke $initialStock.
                StockMovementService::initialStock(
                    $product->id, 
                    $initialStock
                );
                
            } catch (\Throwable $e) {
                // Catat kegagalan kritis. Cek storage/logs/laravel.log jika gagal
                Log::error('FILAMENT/OBSERVER FAILED to record initial stock for Product ID: ' . $product->id, [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Opsional: Hapus produk yang gagal jika Anda ingin menjaga konsistensi database.
                // $product->delete();
                // throw $e; // Atau lempar exception agar Filament menampilkan error
            }
        }
    }
}