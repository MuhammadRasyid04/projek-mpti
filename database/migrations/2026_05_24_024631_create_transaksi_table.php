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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('invoice')->unique();
            $table->foreignId('id_tagihan')->constrained('tagihan', 'id_tagihan')->onDelete('cascade');
            $table->decimal('biaya_sampah', 10, 2);
            $table->decimal('biaya_listrik', 10, 2);
            $table->decimal('biaya_air', 10, 2);
            $table->decimal('biaya_kost', 10, 2);
            $table->decimal('total_bayar', 10, 2);
            $table->timestamp('tgl_request')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
