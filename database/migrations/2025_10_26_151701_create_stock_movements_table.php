<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Product
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // Produk tidak bisa dihapus jika ada history
            
            // Tipe Movement (IN / OUT)
            $table->enum('type', ['in', 'out']);
            
            // Alasan/Kategori Movement (ENUM)
            $table->enum('reason', [
                // IN
                'initial_stock',
                'restock',
                'return_from_order',
                'adjustment_in',
                
                // OUT
                'order',
                'damaged',
                'expired',
                'lost',
                'sample',
                'adjustment_out',
            ]);
            
            // Jumlah yang bergerak
            $table->integer('quantity');
            
            // Snapshot Stock (Wajib)
            $table->integer('stock_before');
            $table->integer('stock_after');
            
            // Reference (Polymorphic: ke Order, Transfer, dll.)
            $table->nullableMorphs('reference');
            
            // Catatan tambahan
            $table->text('notes')->nullable();
            
            // User yang membuat movement
            // WAJIB NULLABLE agar bisa diisi saat Seed/Tinker tanpa login
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            
            $table->timestamps();
            
            // Indeks untuk optimasi query
            $table->index(['product_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};