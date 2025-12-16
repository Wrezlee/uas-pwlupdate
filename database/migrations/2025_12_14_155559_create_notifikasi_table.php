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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->increments('id_notifikasi');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->integer('id_pesanan')->unsigned()->nullable();
            $table->string('pesan', 255);
            $table->enum('status', ['belum_dibaca', 'dibaca']);
            $table->dateTime('tanggal');
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('id_pesanan')
                ->references('id_pesanan')->on('pesanan')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
