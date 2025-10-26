<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockMovementService
{
    /**
     * Create stock movement dan update product stock
     */
    public static function create(array $data): StockMovement
    {
        // 1. Penanganan Data Pre-Transaction (Fallback yang aman)
        $authId = Auth::id();
        $data['created_by'] = $data['created_by'] ?? ($authId ?: 1); // Gunakan ID 1 sebagai fallback teraman

        // Bersihkan data reference jika kosong
        if (empty($data['reference_type']) && empty($data['reference_id'])) {
            unset($data['reference_type'], $data['reference_id']);
        }
        
        // Mulai Transaksi Database
        return DB::transaction(function () use ($data) {
            
            // 2. Ambil product dan lock (penting untuk concurrency)
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);
            
            // 3. Hitung stock baru
            $stockBefore = $product->stock;
            
            if ($data['type'] === 'in') {
                $stockAfter = $stockBefore + $data['quantity'];
            } else {
                $stockAfter = $stockBefore - $data['quantity'];
            }
            
            // 4. Validasi (pencegahan stok negatif)
            if ($stockAfter < 0) {
                throw new \Exception("Stock tidak mencukupi! Stock sekarang: {$stockBefore}.");
            }
            
            // 5. Create stock movement
            $movement = StockMovement::create(array_merge($data, [
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
            ]));
            
            // 6. Update stock produk
            $product->update(['stock' => $stockAfter]);
            
            return $movement;
        });
    }

    /**
     * Shorthand untuk Stock Masuk (IN)
     */
    public static function stockIn(int $productId, string $reason, int $quantity, array $extra = []): StockMovement
    {
        return self::create(array_merge([
            'product_id' => $productId,
            'type' => 'in',
            'reason' => $reason,
            'quantity' => $quantity,
        ], $extra));
    }

    /**
     * Stock awal produk baru
     */
    public static function initialStock(int $productId, int $quantity): StockMovement
    {
        return self::stockIn($productId, 'initial_stock', $quantity, [
            'notes' => 'Stock awal produk saat pembuatan.'
        ]);
    }

    /**
     * Shorthand untuk Stock Keluar (OUT)
     */
    public static function stockOut(int $productId, string $reason, int $quantity, array $extra = []): StockMovement
    {
        return self::create(array_merge([
            'product_id' => $productId,
            'type' => 'out',
            'reason' => $reason,
            'quantity' => $quantity,
        ], $extra));
    }
}