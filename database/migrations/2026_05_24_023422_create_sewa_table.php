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
        Schema::create('sewa', function (Blueprint $table) {
            $table->id('id_sewa');
            $table->foreignId('id_users')->constrained('users', 'id_users')->onDelete('cascade');
            $table->foreignId('id_hunian')->constrained('hunian', 'id_hunian')->onDelete('cascade');
            $table->date('tgl_jatuhtempo');
            $table->enum('status', ['pending', 'aktif', 'nonaktif'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sewa');
    }
};
