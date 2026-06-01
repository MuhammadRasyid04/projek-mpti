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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id('id_tagihan');
            $table->foreignId('id_sewa')->constrained('sewa', 'id_sewa')->onDelete('cascade');
            $table->date('tgl_tagihan');
            $table->decimal('air', 10, 2);
            $table->decimal('listrik', 10, 2);
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
        Schema::dropIfExists('tagihan');
    }
};
