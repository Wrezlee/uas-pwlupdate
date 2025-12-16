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
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->integer('id_pesanan')->unsigned();
            $table->integer('id_barang')->unsigned();
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('id_pesanan')
                ->references('id_pesanan')->on('pesanan')
                ->onDelete('cascade');

            $table->foreign('id_barang')
                ->references('id_barang')->on('barang')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_detail');
    }
};
