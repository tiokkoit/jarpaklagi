<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockMovementService
{
    /**
     * Buat record stock movement dan update product stock
     */
    public static function create(array $data): StockMovement
    {
        // 1️⃣ Pastikan created_by aman
        $authId = Auth::id();
        $data['created_by'] = $data['created_by'] ?? ($authId ?: 1);

        // Bersihkan field reference kalau kosong
        if (empty($data['reference_type']) && empty($data['reference_id'])) {
            unset($data['reference_type'], $data['reference_id']);
        }

        // 2️⃣ Transaksi untuk jaga konsistensi
        return DB::transaction(function () use ($data) {

            // Lock product agar tidak race condition
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            // 🧩 Ambil stok sebelum (default dari tabel products)
            $stockBefore = $product->stock ?? 0;

            // 🧮 Hitung stok sesudah
            if ($data['type'] === 'in') {
                $stockAfter = $stockBefore + $data['quantity'];
            } else {
                $stockAfter = $stockBefore - $data['quantity'];
            }

            // ❌ Cegah stok negatif
            if ($stockAfter < 0) {
                throw new \Exception("Stock tidak mencukupi! Stock sekarang: {$stockBefore}.");
            }

            // 📝 Simpan movement
            $movement = StockMovement::create(array_merge($data, [
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
            ]));

            // 🔄 Update stok produk
            $product->update(['stock' => $stockAfter]);

            return $movement;
        });
    }

    /**
     * Stock Masuk (IN)
     */
    public static function stockIn(int $productId, string $reason, int $quantity, array $extra = []): StockMovement
    {
        return self::create(array_merge([
            'product_id' => $productId,
            'type'       => 'in',
            'reason'     => $reason,
            'quantity'   => $quantity,
        ], $extra));
    }

    /**
     * Stock Keluar (OUT)
     */
    public static function stockOut(int $productId, string $reason, int $quantity, array $extra = []): StockMovement
    {
        return self::create(array_merge([
            'product_id' => $productId,
            'type'       => 'out',
            'reason'     => $reason,
            'quantity'   => $quantity,
        ], $extra));
    }

    /**
     * Stock Awal Produk Baru (Initial Stock)
     * 🧠 Aman: before = 0, after = jumlah awal produk
     */
    public static function initialStock(int $productId, int $quantity): StockMovement
    {
        $product = Product::findOrFail($productId);

        return DB::transaction(function () use ($product, $quantity) {
            // pastikan produk baru, belum ada movement
            $hasMovement = StockMovement::where('product_id', $product->id)->exists();
            if ($hasMovement) {
                throw new \Exception("Produk ini sudah punya riwayat stok, tidak bisa initial stock lagi.");
            }

            // before 0 → after = qty awal
            $movement = StockMovement::create([
                'product_id'   => $product->id,
                'type'         => 'in',
                'reason'       => 'initial_stock',
                'quantity'     => $quantity,
                'stock_before' => 0,
                'stock_after'  => $quantity,
                'notes'        => 'Stock awal produk saat pembuatan.',
                'created_by'   => Auth::id() ?? 1,
            ]);

            // update stok produk
            $product->update(['stock' => $quantity]);

            return $movement;
        });
    }
}
