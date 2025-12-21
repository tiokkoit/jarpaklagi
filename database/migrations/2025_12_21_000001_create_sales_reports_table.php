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
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('phone');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('province');

            // snapshot of product package purchased
            $table->unsignedBigInteger('product_package_id');
            $table->integer('quantity');

            $table->decimal('price', 15, 2);
            $table->decimal('total_price', 18, 2);

            // status in sales report will be one of: CANCEL, SELESAI, DIKEMBALIKAN
            $table->string('status');
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
        Schema::dropIfExists('sales_reports');
    }
};
