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
        Schema::create('hunian', function (Blueprint $table) {
            $table->id('id_hunian');
            $table->string('nama_hunian');
            $table->text('deskripsi_hunian')->nullable();
            $table->enum('status_harian', ['kosong', 'full'])->default('kosong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hunian');
    }
};
