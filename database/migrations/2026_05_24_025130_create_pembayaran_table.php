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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->string('invoice');
            $table->foreign('invoice')->references('invoice')->on('transaksi')->onDelete('cascade');
            $table->string('nama_pengirim');
            $table->string('bukti_trf');
            $table->date('tgl_bayar');
            $table->date('tgl_jatuhtempo');
            $table->enum('status', ['notpaid', 'verif', 'paid'])->default('notpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
