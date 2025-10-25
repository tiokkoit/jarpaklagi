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
        Schema::create('product_packages', function (Blueprint $table) {
            $table->id();

            // Relasi ke produk
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('code')->unique(); // Kode paket, misal MOE01-PK01
            $table->string('name');           // Nama paket, misal "Paket 1"
            $table->integer('pcs_per_package')->default(1); // Isi per paket
            $table->decimal('price', 15, 2);  // Harga jual paket
            $table->boolean('is_active')->default(true);   // Status aktif/tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_packages');
    }
};
