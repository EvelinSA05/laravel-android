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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('idtransaksi');
            $table->string('no_faktur')->unique();
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('nama_pelanggan');
            // $table->integer('idkategori');
            $table->integer('total');
            $table->integer('jumlah_bayar');
            $table->integer('kembali');
            $table->boolean('status');
            $table->integer('no_antrian');
            $table->integer('id');
            $table->boolean('booking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
