<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('phone');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('province');

            // product package reference (must exist)
            $table->unsignedBigInteger('product_package_id');
            $table->integer('quantity');

            // price per package copied from product_packages.price (not nullable)
            $table->decimal('price', 15, 2);
            $table->decimal('total_price', 18, 2);

            // statuses: NEW, CANCEL, DIKIRIM, SELESAI, DIKEMBALIKAN
            $table->string('status')->default('NEW');

            // payment method: COD, TRANSFER
            $table->string('payment');

            $table->timestamps();

            $table->foreign('product_package_id')
                ->references('id')
                ->on('product_packages')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
